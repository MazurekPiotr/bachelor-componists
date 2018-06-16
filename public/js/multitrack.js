 var id = document.getElementById('projectId').dataset.projectId;
 var links;

 var playlist = WaveformPlaylist.init({
     samplesPerPixel: 1500,
     mono: true,
     waveHeight: 150,
     container: document.getElementById('playlist'),
     state: 'cursor',
     colors: {
         waveOutlineColor: 'black',
         timeColor: 'white',
         fadeColor: 'red'
     },
     controls: {
         show: true,
         width: 0
     },
     zoomLevels: [1500, 3000, 5000, 10000, 15000, 20000],
     isAutomaticscroll: true
 });

 showLoading();
 $.get('/api/getSlugsFromProject/' + id, function (response) {
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
