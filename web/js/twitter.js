Event.observe(window, 'load', function (evt) {
  $('tweet_body').onkeyup = function () {
    var text = $F('tweet_body');
    var count = remain(text, true);
    var count2 = remain(text, false);
    var color = '#cccccc';

    if (count < 10) {
      color = '#d40d12';
    }
    else if (count < 20) {
      color = '#5c0002';
    }

    $('count').style.color = color;
    $('count_num').innerHTML = count2;

    if (count != count2) {
      $('count_plus').innerHTML = '+' + (count - count2);
      $('count_plus').show();
    }
    else {
      $('count_plus').hide();
    }

    if (count < 0 || count == 140) {
      $('submit').disable();
    }
    else {
      $('submit').enable();
    }
  };
  $('tweet_body').onkeyup();
});

function remain(str, shorten) {
  var count = 140;

  if (shorten) {
    str = str.replace(/https?:\/\/[0-9a-zA-Z\-_.!~\*'()@\?\+,\$\/#%;:&=]{14,}/g, 'http://bit.ly/******');
  }

  return count - str.length;
}
