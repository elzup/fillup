<?php

define('tb_lecture', 'fillup_lecture');
define('tb_word', 'fillup_word');
define('tb_comment', 'fillup_comment');

class Lecture {
	private $id_lecture;
	public function getId_lecture() {
		return $this->id_lecture;
	}
	private $name;
	public function getName() {
		return $this->name;
	}
	private $date;
	public function getdate() {
		return $this->date;
	}
	public function setName($value) {
		$this->name = $value;
	}
	private $term;
	public function getTerm() {
		return $this->term;
	}
	public function setTerm($value) {
		$this->term = $value;
	}
	private $term_d;
	public function getTermD() {
		return $this->term_d;
	}
	private $term_t;
	public function getTermT() {
		return $this->term_t;
	}
	private $timestamp;

	private $list_comment;
	private $list_word;

	private $count_comment;
	private $count_word;

	private $state_time;
	private $state_time_pro;

	public function getStateTimePro() {
		return $this->state_time_pro;
	}


	public function __construct($id_lecture = null) {
		if (empty($id_lecture)) return;
		$this->id_lecture = $id_lecture;
		$this->loadLecture();
	}

	// ----------------- DB manage ----------------- //
	public static function insertLecture($name, $term, $date) {
		$parameter = array (
				'name'       => $name,
				'term'       => $term,
				'date'       => $date,
		);
		if (DB::isExistCond(tb_lecture, $parameter)) {
			echo "is already registed";
		} else {
			DB::insert(tb_lecture, $parameter);
		}
	}

	public static function insertWord($id_lecture, $word, $index, $page, $id_user) {
		$parameter = array (
				'id_lecture' => $id_lecture,
				'word'       => $word,
				'index'      => $index,
				'page'       => $page,
				'id_user'    => $id_user,
		);
		DB::insert(tb_word, $parameter);
	}

	public static function insertComment($id_lecture, $text, $id_user) {
		$parameter = array (
				'id_lecture' => $id_lecture,
				'text'       => $text,
				'id_user'    => $id_user,
		);
		DB::insert(tb_word, $parameter);
	}

	public function loadLecture () {
		$condition['id_lecture'] = $this->id_lecture;
		$result = DB::getRowOne(tb_lecture, $condition);
		$this->structFromData($result);
	}

	public function loadWord () {
		$condition['id_lecture'] = $this->id_lecture;
		$result = DB::getRow(tb_word, $condition);
		foreach ($result as $r) {
			$this->list_word[] = new Word($r);
		}
	}

	public function loadComment () {
		$condition['id_lecture'] = $this->id_lecture;
		$result = DB::getRow(tb_comment, $condition);
		foreach ($result as $r) {
			$this->list_comment[] = new Comment($r);
		}
	}

	public function structFromData($data) {
		$this->id_lecture = $data['id_lecture'];
		$this->name       = $data['name'];
		$this->term       = $data['term'];
		$this->date       = $data['date'];
		$this->term_d     = substr($this->term, 0, 1);
		$this->term_t     = substr($this->term, 1);
		$this->timestamp  = strtotime($data['timestamp']);
		$this->setTimeState();

		$this->count_word    = $data['count_word'];
		$this->count_comment = $data['count_comment'];
	}

	public function setTimeState() {
		if (date('Y-m-d') == date('Y-m-d', strtotime($this->date))) {
			$result = getStateTimeLecture($this->term_t);
			if ($result == time_after || $result == time_before) {
				$this->state_time = $result;
			} else {
				$this->state_time = time_going;
				$this->state_time_pro = $result;
			}
			if (isset($_GET['deba'])) {
				echo $result;
				print_r($this);
				exit;
			}
		}
		else $this->state_time = time_before;
	}

	// ----------------- html manage ----------------- //



	public function printListWordDiv () {
		if (empty($this->list_word)) $this->loadWord();
		if (empty($this->list_word)) {
			Word::printTr('-1', '登録されていません', '---');
		} else {
			foreach ($this->list_word as $w) {
				if(method_exists($w, 'printListTr'))
					$w->printListTr();
			}
		}
	}

	public function printListCommnetDiv () {
		if (empty($this->list_comment)) $this->loadComment();
		if (empty($this->list_comment)) {
			Comment::printTr(-1, 'Sys', 'ざっぷ', '20XX-01-05 00:00:00', '---', 'まだコメントがありません');
		}
		else {
			foreach ($this->list_comment as $c) {
				$c->printListTr();
			}
		}
	}


	public function printLinkListDiv($num) {
		$text_term = $this->termTextSmart();
		$date = date(fo_date, $this->timestamp);
		echo $hd01 =<<<hd01

<div class="link_list_div" num="{$num}">
	<span class="term {$this->state_time}">{$text_term}</span>
	<span class="date">{$this->date}</span>
    <a href="./bb?id={$this->id_lecture}">
	<span class="name_lecture">{$this->name}</span>
    </a>
</div>

hd01;
		//	<span class="timestamp">{$date}</span>
	}

	public function printListTr() {
		$text_term = $this->termTextSmart();
		$date = date(fo_date, $this->timestamp);
		$tr_tail = "";
		if ($this->state_time == time_going) $tr_tail = ' class="success"';
		elseif ($this->state_time == time_after) $tr_tail = ' class="active"';

		echo $e =<<<EOF

      <tr{$tr_tail}>
        <td>{$text_term}</td>
        <td><a href="./bb?id={$this->id_lecture}">{$this->name}</a></td>
        <td>{$this->date}</td>
        <td>{$this->count_comment}</td>
        <td>{$this->count_word}</td>
      </tr>

EOF;
	}

	public function printLectureNameSpan() {
		echo $hd =<<<EOF

        <span class="title-span"> {$this->name}</span>

EOF;

	}

	private function termText() {
		return toStrDay($this->term_d).toStrTime($this->term_t);
	}
	private function termTextSmart() {
		$tt = $this->termText();
		return mb_substr($tt, 0, 1).mb_substr($tt, 2, 1);
	}
}


class Word {
	public $point;
	public $word;
	public $index;
	public function __construct($data) {
		$this->point = $data['point'];
		$this->word  = h($data['word']);
		$this->index = $data['index'];
	}

	//     public function printListDiv() {
	//         echo $e =<<<EOF
	// <div class="word-list-div" num={$this->index}>
	//     <span class="word">{$this->word}</span>
	//     <input value="★" num="{$this->index}" type="button" class="fav-word-button" />
	//     <span class="point">{$this->point}</span>
	// </div>

	// EOF;
	//     }

	public function printListTr() {
		Word::printTr($this->index, $this->word, $this->point);
	}

	public static function printTr($index, $word, $point) {
		$button = ($index == -1) ? "---" :
		'    <button wc="w" type="button" num="'.$index.'" class="btn btn-warning btn-fav">★</button>';
		echo $e = <<<EOF
<tr class="word-tr">
  <td class="index">{$index}</td>
  <td title="{$word}" class="word">{$word}</td>
  <td class="point">{$point}</td>
  <td class="fav">
          $button
  </td>
</tr>

EOF;
	}



}

class Comment {
	public $point;
	public $text;
	public $index;
	public $type;
	public $id_user;
	static public $short_des = array (
				'n' => 'コメ',
				'u' => 'リンク',
				'q' => '質問',
				'r' => '返答',
//				'n' => '-N-',
//				'u' => 'URL',
//				'q' => 'Qus',
//				'r' => 'Rep',
				'e' => '問題',
		);

	// text type -> html
	static $text_des = array (
				'n' => '普通のコメントを送信します。テストに出そうなポイントや、自分が重要であると思う事柄についてです。',
				'u' => '参考リンクを送信します。外聞サイトへのリンクや、ファイルをアップロードしたDropBoxリンクなどのタイプです。',
				'q' => '疑問に思ったことや、質問を送信します。大事なポンとであることがあるので、どんどｎ質問しましょう',
				'r' => '質問コメントに対する返答を行います。',
				'e' => '自分で作成した問題を投稿します。{波括弧}を使うことでテキストをトリップにすることが出来ます。<br />解答部分を一緒に投稿する際や、虫食い問題を作成する際に使いましょう。',
			);
	public function __construct($data) {
		$this->point     = $data['point'];
		$this->text      = h($data['text']);
		$this->index     = $data['index'];
		$this->type      = $data['type'];
		$this->id_user   = $data['id_user'];
		$this->timestamp = $data['timestamp'];
	}

	public function printListDiv() {
		$comment_text = '<span class="text">'.$this->text.'</span>';
		$id_user = substr(hash('md5', $this->id_user), 3, 8);
		if ($this->type == 'u') {
			$ss = preg_split('/'.st_deli.'/', $this->text);
			$title = (empty($ss[0])) ? '-no get title': $ss[0];
			$url = $ss[1];
			$comment_text =<<<EOF
             <span class="text">
                    <span class='url-head'>[ $title ]</span><br />
                    <a href="$url" target="_blank">
                            $url
                    </a>
             </span>
EOF;
		}
		echo $e =<<<EOF

<div class="comment-list-div" num={$this->index}>
    <div class="comment-header-div">
        <span class="index">{$this->index}</span>
        <span class="type" type="{$this->type}">{$this->typeStr()}</span>
        <!--span class="id_user">{$id_user}</span-->
        <input value="★" num="{$this->index}" type="button" class="btn" />
        <span class="point">{$this->point}</span>
    </div>
    <div class="comment-text-div">
    {$comment_text}
    </div>
</div>
EOF;
	}

	private function typeStr() {
		return Comment::$short_des[$this->type];
	}

	public function timeStr() {
		return date(fo_date, strtotime($this->timestamp));
	}

	public function printListTr() {
		Comment::printTr($this->index, $this->typeStr(), $this->id_user, $this->timeStr(), $this->point, $this->text);
	}

	public static function printTr($index, $type, $name, $time, $point, $text) {
		$text = urlChangeLink($text);
		$text = keywordChangeBold($text);
		$text = tripText($text);

		$button = ($index == -1) ? "---" :
		'    <button wc="c" type="button" num="'.$index.'" class="btn btn-warning btn-fav">★</button>';
		$name = "名無しさん";
		echo $e = <<<EOF

<tr class="comment-head">
  <td class="index">{$index}</td>
  <td class="type">{$type}</td>
  <td class="user-name">{$name}</td>
  <td class="time">{$time}</td>
  <td class="point">{$point}</td>
  <td class="fav">
$button
  </td>
</tr>
<tr>
  <td colspan="6">{$text}</td>
</tr>

EOF;
	}
}


?>