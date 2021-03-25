<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Record Audio</title>
</head>
<body>
  <button id="request">
   Request Permission
  </button>

  <button id="start" disabled>
    Start
  </button>

  <button id="stop" disabled>
    Stop
  </button>

  <div id="output" style="margin-top: 16px"></div>
  <div id="download"></div>

  <script>
    let stream,
      recorder,
      chunks = [],
      request = document.getElementById('request'),
      start = document.getElementById('start'),
      stop = document.getElementById('stop'),
      output = document.getElementById('output'),
      download = document.getElementById('download');

    request.addEventListener('click', function (e) {
      navigator.mediaDevices.getUserMedia({ audio: true })
        .then(function (_stream) {
          stream = _stream;

          start.removeAttribute('disabled');
          e.target.disabled = true;

          recorder = new MediaRecorder(stream);
          recorder.ondataavailable = function (e) {
            chunks.push(e.data);

            if (recorder.state === 'inactive') {
              showAudio();
            }
          }
        });
    });

    start.addEventListener('click', function (e) {
      e.target.disabled = true;
      stop.removeAttribute('disabled');

      chunks = [];
      recorder.start();
    });

    document.getElementById('stop').addEventListener('click', function (e) {
      e.target.disabled = true;
      start.removeAttribute('disabled');

      recorder.stop();
    });

    function showAudio() {
      const blob = new Blob(chunks, { type: 'audio/ogg' });
      const url = URL.createObjectURL(blob);

      const audio = document.createElement('audio');
      audio.controls = true;
      audio.src = url;

      const a = document.createElement('a');
      a.href = url;
      a.download = 'audio.ogg';
      a.innerHTML = 'Download Recorded Audio';

      output.innerHTML = '';
      output.appendChild(audio);

      download.innerHTML = '';
      download.appendChild(a);
    }
  </script>
</body>
</html>
