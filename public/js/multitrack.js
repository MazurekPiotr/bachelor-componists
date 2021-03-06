var id = document.getElementById('projectId').dataset.projectId;
var userId = document.getElementById('projectId').dataset.userId;
var links;

var playlist = WaveformPlaylist.init({
    samplesPerPixel: 1500,
    mono: true,
    waveHeight: 150,
    container: document.getElementById('playlist'),
    state: 'cursor',
    colors: {
      waveOutlineColor: '#E0EFF1',
      timeColor: 'grey',
      fadeColor: 'black'
    },
    controls: {
        show: true,
        width: 200
    },
    zoomLevels: [1500, 3000, 5000, 10000, 15000, 20000],
    isAutomaticscroll: true,
    timescale: true
});
var ee = playlist.getEventEmitter();

showLoading();
$.get('/api/getSlugsFromProject/' + id + '/' + userId, function (response) {
  console.log(response);

    playlist.load(response).then(function() {
      $('.automatic-scroll').attr('checked', true);
      ee.emit("automaticscroll", $('.automatic-scroll').is(':checked'));
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
