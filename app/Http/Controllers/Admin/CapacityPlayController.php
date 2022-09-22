<?php

namespace App\Http\Controllers\Admin;

use App\Events\EndGameEvent;
use App\Events\NextGameEvent;
use App\Events\PlayGameEvent;
use App\Events\UpdateGameEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Capacity\CapacityPlay;
use App\Services\Modules\MAnswer\MAnswerInterface;
use App\Services\Modules\MExam\MExamInterface;
use App\Services\Modules\MQuestion\MQuestionInterface;
use App\Services\Modules\MResultCapacity\MResultCapacityInterface;
use App\Services\Modules\MResultCapacityDetail\MResultCapacityDetailInterface;
use App\Services\Traits\TResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CapacityPlayController extends Controller
{
    use TResponse;
    public function __construct(
        public MExamInterface $examRepo,
        public MQuestionInterface $questionRepo,
        public MResultCapacityInterface $resultCapacityRepo,
        public MResultCapacityDetailInterface $resultCapacityDetailRepo,
        public MAnswerInterface $answerRepo
    ) {
    }

    public function index()
    {
        $exams = $this->examRepo->getExamCapacityPlay([], ['questions']);
        return view('pages.capacity-play.index', compact('exams'));
    }

    public function create()
    {
        $data = [];
        $data['questions'] = $this->questionRepo->getAllQuestion();
        return view('pages.capacity-play.create', $data);
    }

    public function store(CapacityPlay $request)
    {
        DB::beginTransaction();
        try {
            $exam  = $this->examRepo->storeCapacityPlay($request);
            DB::commit();
            return redirect()->route('admin.capacit.play.show', ['id' => $exam->id]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with("error", "Không thể thêm mới trò chơi ! Lỗi " . $th->getMessage())
                ->withInput($request->input());
        }
    }

    public function autTokenPlay($code)
    {
        if ($exam = $this->examRepo->getExamBtyTokenRoom($code)) {
            return $this->responseApi(true, [
                "exam" => $exam
            ]);
        }
        return $this->responseApi(false, "Không tìm thấy trò chơi !");
    }

    public function show($id)
    {
        // $result = $this->resultCapacityRepo->find(237);
        // $cores = (int) $result->true_answer / (int) count(json_decode($data['exam']->room_progress) ?? []);
        // dd($cores);
        $data = [];
        $data['exam'] = $this->examRepo->findById($id);
        $data['exam']->load(['questions' => function ($q) {
            return $q->with(['answers']);
        }]);
        $data['ranks'] = $this->resultCapacityRepo->where([
            "exam_id" => $id,
        ], ['user'], true, 100);
        // dd($data['exam']->toArray());
        return view('pages.capacity-play.show', $data);
    }

    public function userContinueTest($code)
    {
        $exam = $this->examRepo->getExamBtyTokenRoom($code, ['questions' => function ($q) {
            return $q->with(['answers:id,question_id,content']);
        }], ['questions']);
        if (!$exam) return $this->responseApi(false);
        $data = [];
        $data['exam'] = $exam;
        $data['ranks'] = $this->resultCapacityRepo->where([
            "exam_id" => $exam->id,
        ], ['user'], true, $exam->status == 2 ? 100 : 5)->toArray();
        $data['rank'] = $this->resultCapacityRepo->where([
            "exam_id" => $exam->id,
            "user_id" => auth('sanctum')->id()
        ], ['user']) ?? false;
        if ($data['rank']) $data['rank'] = $data['rank']->toArray();
        $PROGRESS = json_decode($exam->room_progress) ?? [];
        if (count($PROGRESS) == 0) return $this->responseApi(true, $data + ['status' => false]);
        $data['question'] = $this->questionRepo->findById(end($PROGRESS), ['answers:id,question_id,content']);
        return $this->responseApi(true, $data);
    }

    public function start($code)
    {
        $exam = $this->examRepo->getExamBtyTokenRoom($code, ['questions' => function ($q) {
            return $q->with(['answers:id,question_id,content']);
        }], ['questions']);
        // $data['question'] = $exam->questions[0];
        // return view('pages.capacity-play.play', $data);

        //
        $data = [];
        $data['exam'] = $exam;
        $data['ranks'] = $this->resultCapacityRepo->where([
            "exam_id" => $exam->id,
            // "user_id" => auth('sanctum')->id(),
        ], ['user'], true, 5)->toArray();
        $data['rank'] = $this->resultCapacityRepo->where([
            "exam_id" => $exam->id,
            "user_id" => auth('sanctum')->id()
        ], ['user']);

        if ($data['rank']) $data['rank'] = $data['rank']->toArray();
        if ($exam->room_token) {
            if (count(json_decode($exam->room_progress) ?? []) != 0) {
                $PROGRESS = json_decode($exam->room_progress) ?? [];

                if ($exam->questions_count == count($PROGRESS) && $exam->status == 2) {
                    return redirect()->route('admin.capacit.play.show', ['id' => $exam->id])->with('error', 'Trò chơi đã kết thúc !');
                } else {
                    if (request()->has('next')) {

                        if (in_array(request()->next, $PROGRESS)) {
                            $data['question'] = $this->questionRepo->findById(request()->next, ['answers:id,question_id,content']);
                            return view('pages.capacity-play.play', $data)->with('error', 'Câu hỏi đã làm !');
                        }

                        $questions = $exam->questions->map(function ($exam) {
                            return $exam->id;
                        });

                        if (!in_array(request()->next, $questions->toArray())) {
                            $data['question'] = $this->questionRepo->findById(request()->next, ['answers:id,question_id,content']);
                            return view('pages.capacity-play.play', $data)->with('error', 'Không tồn tại câu hỏi !');
                        }

                        $data['question'] = $this->questionRepo->findById(request()->next, ['answers:id,question_id,content']);
                        array_push($PROGRESS, $data['question']->id);
                        $data['exam'] = $this->examRepo->updateCapacityPlay($exam->id, [
                            "room_progress" => json_encode($PROGRESS)
                        ]);

                        broadcast(new NextGameEvent($code, $exam->token, $data['question']->toArray(), $data['ranks']));
                        return view('pages.capacity-play.play', $data);
                    } else {

                        $data['question'] = count($PROGRESS) == 0 ? $exam->questions[0] : $this->questionRepo->findById(end($PROGRESS));
                        return view('pages.capacity-play.play', $data);
                    }
                }
            } else {

                $data['question'] = request()->next ?
                    $this->questionRepo->findById(request()->next, ['answers:id,question_id,content']) :
                    $exam->questions[0];
                $roomProgressUpdate = [$data['question']->id];
                $data['exam'] = $this->examRepo->updateCapacityPlay($exam->id, [
                    "room_progress" => json_encode($roomProgressUpdate)
                ]);
                broadcast(new PlayGameEvent($code, $exam->token, $data['question']->toArray(), $data['ranks']));
                return view('pages.capacity-play.play', $data);
            }
        } else {

            $data['question'] = $data['exam']->questions[0];
            $exam = $this->examRepo->updateCapacityPlay($exam->id, [
                "room_token" => MD5(uniqid() . time()),
                "room_progress" => json_encode([$data['question']->id])
            ]);


            broadcast(new PlayGameEvent($code, $exam->token, $data['question']->toArray(), $data['ranks']));
            $data['exam'] = $exam;
            return view('pages.capacity-play.play', $data);
        }
    }

    public function submitQuestionCapacityPlay(Request $request, $token)
    {
        $exam = $this->examRepo->getExamBtyTokenRoom($token);
        $answers = $request->answers;
        $flagIsCorrect = 0;
        $flagIsNotCorrect = 0;
        $flagDonot = 0;
        $answersIsCorrect = $this->questionRepo->findById($request->question_id, ['answers' => function ($q) {
            return $q->where('is_correct', 1);
        }])->answers->map(function ($data) {
            return $data->id;
        });
        if (count($answers) >= 0) {
            if (count($answersIsCorrect) == count($answers)) {
                $count = 0;
                foreach ($answersIsCorrect as $k => $v) {
                    $flag = false;
                    if (in_array($v, $answers)) $flag = true;
                    if ($flag) $count = $count + 1;
                }
                if ($count == count($answersIsCorrect)) {
                    $flagIsCorrect = 1;
                } else {
                    $flagIsNotCorrect = 1;
                }
            } else {
                $flagIsNotCorrect = 1;
            }
        } else {
            $flagDonot = 1;
        }
        // if (count($request->answers) > 0) {
        //     foreach ($answers as $answer) {
        //         if ($this->answerRepo->findById($answer)->is_correct) {
        //             $flagIsCorrect++;
        //         } else {
        //             $flagIsNotCorrect++;
        //         }
        //     }
        // } else {
        //     $flagDonot = 1;
        // }

        DB::beginTransaction();
        try {

            if ($resultCapacity = $this->resultCapacityRepo->where([
                "user_id" => auth('sanctum')->id(),
                "exam_id" => $exam->id,
            ])) {

                $true_answer = $resultCapacity->true_answer + $flagIsCorrect;
                $false_answer = $resultCapacity->false_answer + $flagIsNotCorrect;
                $donot_answer = $resultCapacity->donot_answer + $flagDonot;
                if ($request->flagEvent) {
                    $scores = (int) $true_answer / (int) count(json_decode($exam->room_progress) ?? []);
                    $this->resultCapacityRepo->update($resultCapacity->id, [
                        "true_answer" => $true_answer,
                        "false_answer" => $false_answer,
                        "donot_answer" => $donot_answer,
                        "scores" => $scores * $exam->max_ponit,
                        'status' => 1
                    ]);
                } else {
                    $this->resultCapacityRepo->update($resultCapacity->id, [
                        "true_answer" => $true_answer,
                        "false_answer" => $false_answer,
                        "donot_answer" => $donot_answer
                    ]);
                }
            } else {
                $resultCapacity = $this->resultCapacityRepo->create([
                    "status" => 0,
                    "type" => 1,
                    "user_id" => auth('sanctum')->id(),
                    "exam_id" => $exam->id,
                    "true_answer" => $flagIsCorrect,
                    "false_answer" => $flagIsNotCorrect,
                    "donot_answer" => $flagDonot
                ]);
            }

            if ($flagDonot == 1) {

                $this->resultCapacityDetailRepo->create([
                    "result_capacity_id" => $resultCapacity->id,
                    "question_id" => $request->question_id,
                    "answer_id" => null
                ]);
            } else {

                foreach ($answers as $answer) {
                    $this->resultCapacityDetailRepo->create([
                        "result_capacity_id" => $resultCapacity->id,
                        "question_id" => $request->question_id,
                        "answer_id" => $answer
                    ]);
                }
            }

            DB::commit();
            $dataRank = $this->resultCapacityRepo->where([
                "exam_id" => $exam->id,
            ], ['user'], true, 5);
            $rank = $this->resultCapacityRepo->where([
                "exam_id" => $exam->id,
                "user_id" => auth('sanctum')->id()
            ], ['user']);
            if ($rank) $rank = $rank->toArray();
            broadcast(new UpdateGameEvent($token, $dataRank->toArray()))->toOthers();

            return $this->responseApi(true, [
                "ranks" => $dataRank,
                'rank' => $rank
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->responseApi(false, $th->getMessage());
        }
    }

    public function end($code)
    {
        // broadcast(new EndGameEvent($code));
        // return 1;
        $exam = $this->examRepo->getExamBtyTokenRoom($code, ['questions' => function ($q) {
            return $q->with(['answers:id,question_id,content']);
        }], ['questions']);
        $data = [];
        $data['exam'] = $exam;
        $PROGRESS = json_decode($exam->room_progress) ?? [];
        if (count($PROGRESS) == $exam->questions_count) {
            DB::beginTransaction();
            try {
                $data['exam'] = $this->examRepo->updateCapacityPlay($exam->id, [
                    "status" => 2
                ]);
                // $this->resultCapacityRepo->updateStatusEndRenderScores([
                //     'exam' => $exam
                // ]);

                DB::commit();
                broadcast(new EndGameEvent($code));
                return redirect()->route('admin.capacit.play.show', ['id' => $exam->id]);
            } catch (\Throwable $th) {
                DB::rollBack();
                $data['question'] = $exam->questions[end($PROGRESS)];
                return view('pages.capacity-play.play', $data)->with('error', $th->getMessage());
            }
        } else {
            $data['question'] = count($PROGRESS) == 0 ? $exam->questions[0] : $exam->questions[end($PROGRESS)];
            return view('pages.capacity-play.play', $data)->with('error', 'Chưa hoàn thành hết các câu hỏi !');
        }
    }
}