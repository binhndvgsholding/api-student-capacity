// Set list query url
const aListQuery = [
    "page",
    "major_id",
    "q",
    "sort_by",
    "status",
    "sort",
    "contest_id",
    "type_exam_id",
    "start_time",
    "end_time",
    "pass_date",
    "upcoming_date",
    "miss_date",
];

// Search params
let searchParams = new URLSearchParams(window.location.search);

//Set up toast
toastr.options = {
    closeButton: true,
    debug: false,
    newestOnTop: false,
    progressBar: true,
    positionClass: "toastr-bottom-left",
    preventDuplicates: false,
    onclick: null,
    showDuration: "300",
    hideDuration: "1000",
    timeOut: "5000",
    extendedTimeOut: "1000",
    showEasing: "swing",
    hideEasing: "linear",
    showMethod: "fadeIn",
    hideMethod: "fadeOut",
};

// Funtion check url
function checkUrlOut(key, value, valueAdd = "") {
    loadTast();
    if (window.location.href.indexOf("?")) {
        aListQuery.map(function (data) {
            if (data == key) {
                url = url + "&" + key + "=" + value;
            } else {
                if (searchParams.has(data)) {
                    url = url + "&" + data + "=" + searchParams.get(data);
                }
            }
        });
        return (window.location = url + valueAdd);
    }
    window.location = window.location.href + "?" + key + "=" + value;
    return false;
}

// Loas tast
function loadTast() {
    toastr.info("Chương trình đang chạy ...");
}
// Page
const formatPage = {
    refresh: function () {
        $(".refresh-btn").on("click", function () {
            loadTast();
            window.location = url;
            return false;
        });
    },
    formatDatabase: function () {
        $(".format-database").on("click", function () {
            loadTast();
            window.location =
                url + "sort_by=" + $(this).data("key") + "&sort=" + sort;
            return false;
        });
    },
    searchData() {
        $(".ip-search").on("keyup", function (e) {
            if (e.keyCode == 13) {
                checkUrlOut("q", $(this).val());
            }
        });
    },
    startTime() {
        $(".btn-time").on("click", function (e) {
            e.preventDefault();
            let start_time = $(".start_time").val();
            let end_time = $(".end_time").val();
            if (new Date(start_time).getTime() >= new Date(end_time).getTime())
                return alert(
                    "Thời gian kết thúc phải lớn hơn thời gian bắt đầu thời gian bắt đâu !"
                );
            window.location =
                url + "&start_time=" + start_time + "&end_time=" + end_time;
        });
    },
    showPage() {
        $(".btn-show").hide();
        $(".btn-hide").on("click", function () {
            $(".card-format").hide(1000);
            $(this).hide();
            $(".btn-show").show(500);
        });
    },
    hidePage() {
        $(".btn-show").on("click", function () {
            $(".card-format").show(1000);
            $(".btn-hide").show(500);
            $(this).hide();
        });
    },
    setUpRangpake() {
        $("#kt_daterangepicker_2").daterangepicker(
            {
                timePicker: true,
                startDate: moment(),
                endDate: moment(),
                locale: {
                    format: "DD/MM/YYYY hh:mm:ss A",
                },
            },
            function (start, end) {
                loadTast();
                window.location =
                    url +
                    "&start_time=" +
                    moment(start).format("YYYY-MM-DDThh:mm") +
                    "&end_time=" +
                    moment(end).format("YYYY-MM-DDThh:mm");
                return false;
            }
        );
    },
};

formatPage.refresh();
formatPage.formatDatabase();
formatPage.searchData();
formatPage.startTime();
formatPage.showPage();
formatPage.hidePage();
formatPage.setUpRangpake();
