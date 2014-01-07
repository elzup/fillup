$(function() {
    switchTypeButton();
    $('button#c-button-n').click();
    listPointButton();
    setupSwitchTerm();
    setupButtonListComment();
});

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

// ----------------- script ----------------- //
function backPost ( to, name, data ){
    var form = $('#hidden-post-form');
    var input = $('#hidden-post-input' );
    input.attr( 'name' ,  name);
    input.attr( 'value' , data );
    form.attr( 'action' , to );
    form.submit();
}






