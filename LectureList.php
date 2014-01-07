<?php


Class LectureList {

    public $list_lecture;

    public function __construct($date = 0) {
        $list_lecture = array();

        if (empty($date)) $date = date('Y-m-d');
        $this->loadLectureListDay($date);
    }


    private function loadLectureListDay($date) {

        $result = DB::getTable(tb_lecture, null, array('date' => $date));

        foreach($result as $rec) {
            $newl = new Lecture();
            $newl->structFromData($rec);
            $this->list_lecture[$newl->getTermT()][] = $newl;
        }
    }
    public function addDebugPage() {
        $result = DB::getTable(tb_lecture, null, array('id_lecture' => '9999'), 1);
        $newl = new Lecture();
        $newl->structFromData($result[0]);
        array_unshift($this->list_lecture, $newl);

    }
    public function addTempPage() {
        $result = DB::getTable(tb_lecture, null, array('id_lecture' => '10000'), 1);
        $newl = new Lecture();
        $newl->structFromData($result[0]);
        array_unshift($this->list_lecture, $newl);
    }

    public function printListDivs () {
        for($i = 1; $i < 8; $i++) $this->printListTime($i);
        $this->printListDay();
    }

    public function printListTime($term_t) {
        if (empty($this->list_lecture[$term_t])) {

        echo $e =<<< EOF
<div id="list-lecture-{$term_t}" class="table-responsive">
  <h3>{$term_t}限の講義リスト</h3>
        {$term_t}限の講義はありません。
</div>
EOF;
            return ;
        }
        echo $e =<<< EOF
<div id="list-lecture-{$term_t}" class="table-responsive">
  <h3>{$term_t}限の講義リスト</h3>
  <table class="table table-striped table-bordered table-hover table-nonfluid">
    <thead>
      <tr>
        <th>#:</th>
        <th>Lecture_name</th>
        <th>Date</th>
        <th>Comment_num</th>
        <th>Word_num</th>
      </tr>
    </thead>
    <tbody>
EOF;
        $this->printListTbody($term_t);
        echo $e =<<< EOF
    </tbody>
  </table>
</div>
EOF;
    }

    public function printListDay() {
        echo $e =<<< EOF
<div id="list-lecture-day" class="table-responsive">
  <h3>一日の講義リスト</h3>
  <table class="table table-striped table-bordered table-hover table-nonfluid">
    <thead>
      <tr>
        <th>#:</th>
        <th>Lecture_name</th>
        <th>Date</th>
        <th>Comment_num</th>
        <th>Word_num</th>
      </tr>
    </thead>
    <tbody>
EOF;
        for($i = 1; $i < 8; $i++) $this->printListTbody($i);
        echo $e =<<< EOF
    </tbody>
  </table>
</div>
EOF;
    }

    public function printListTbody($term_t) {
        if (empty($this->list_lecture[$term_t])) return;

        foreach($this->list_lecture[$term_t] as $lec)
            $lec->printListTr();
    }

}

?>
