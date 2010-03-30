Event.observe(window, 'load', function (evt) {
  $('tweet_body').onkeyup = function () {
    var text = $F('tweet_body');
    var remains = 140 - text.length;
    var color = '#cccccc';

    if (remains < 10) {
      color = '#d40d12';
    }
    else if (remains < 20) {
      color = '#5c0002';
    }

    $('count').style.color = color;
    $('count').innerHTML = remains;

    if (remains < 0 || remains == 140) {
      $('submit').disable();
    }
    else {
      $('submit').enable();
    }
  };
  $('tweet_body').onkeyup();
});
