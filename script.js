$(function() {
    switchTypeButton();
    $('button#c-button-n').click();
    listPointButton();
    setupSwitchTerm();
//    setupMoveButtons();
    setupButtonListComment();
    setupTrip();
});

function setupTrip () {
    $('.word-trip').hover(
    function() {
        $(this).css('background', 'white');
    },
    function () {
        $(this).css('background', 'orange');
    }
    );
    
}

function setupSwitchTerm() {
    var bw = $('#sw-time-table').children().children();
    bw.children('button').click(function (){
        var term = $(this).attr('term');
        console.log(term);
        $('[id^=list-lecture-]').hide();
        $('#list-lecture-' + term).show();
    });
    bw.children('button:first-child').click();
}


function switchTypeButton() {
    $('button[id^="c-button"]').click(function() {
        var type = $(this).attr('id').substr(9, 1);
        $('input#c-type').val(type);
        var text = getDisc(type);
        $('#type-description-div').html(text);
        console.log(type + ":switched");
    });
}

function listPointButton() {
    $('.btn-fav').click(function() {
        var num = $(this).attr('num');
        var wc = $(this).attr('wc');
        backPost('incPoint', 'data', wc + "" + num);
        console.log(num + ':word_favoed');
        var nb = $(this).parent().prev();
        nb.html(parseInt(nb.html()) + 1);
    });
}

function setupButtonListComment() {
    $('#sw-show-rate-all').click(function() {
        $('div#comment-box-div>table>tbody>tr').slideDown();
        document.cookie = 'show=' + encodeURIComponent( all );
    });
    $('#sw-show-rate-10').click(function() {
        $('div#comment-box-div>table>tbody>tr').show();
        $('div#comment-box-div>table>tbody>tr:nth-last-of-type(20)').prevAll().slideUp();
        document.cookie = 'show=' + encodeURIComponent( 10 );
    });
    $('#sw-show-rate-20').click(function() {
        $('div#comment-box-div>table>tbody>tr').show();
        $('div#comment-box-div>table>tbody>tr:nth-last-of-type(40)').prevAll().slideUp();
        document.cookie = 'show=' + encodeURIComponent( 20 );
    });
}


var descLib = [
	'普通のコメントを送信します。テストに出そうなポイントや、自分が重要であると思う事柄についてです。',
	'参考リンクを送信します。外聞サイトへのリンクや、ファイルをアップロードしたDropBoxリンクなどのタイプです。',
	'疑問に思ったことや、質問を送信します。大事なポンとであることがあるので、些細な事も質問しましょう',
	'質問コメントに対する返答を行います。',
	'自分で作成した問題を投稿します。{波括弧}を使うことでテキストをトリップにすることが出来ます。<br />解答部分を一緒に投稿する際や、虫食い問題を作成する際に使いましょう。',
]

function getDisc (c) {
    var ic = "nuqre".split("");
    for (var i = 0; i < 5; i++) {
        if (ic[i] == c) {
            return descLib[i];
       }
    }
}


// ----------------- script ----------------- //
function backPost ( to, name, data ){
    var form = $('#hidden-post-form');
    var input = $('#hidden-post-input' );
    input.attr( 'name' ,  name);
    input.attr( 'value' , data );
    form.attr( 'action' , to );
    form.submit();
}

