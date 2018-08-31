var id = document.getElementById('projectId').dataset.projectId;
//var userId = document.getElementById('projectId').dataset.userId;
var links;

var WaveformPlaylist = require('waveform-playlist');

var playlist = WaveformPlaylist.init({
    samplesPerPixel: 1500,
    mono: true,
    waveHeight: 150,
    container: document.getElementById('playlist'),
    state: 'cursor',
    controls: {
        show: true,
        width: 200
    },
    zoomLevels: [1500, 3000, 5000, 10000, 15000, 20000],
    isAutomaticscroll: true,
    timescale: true
});

showLoading();
$.get('/api/getSlugsFromProject/' + id, function (response) {
  console.log(response);

    playlist.load(response).then(function() {

      hideLoading();
    });
    playlist.initExporter();
});

function showLoading() {
    $(".loader").show();
};

function hideLoading() {
    $(".loader").hide();
};
