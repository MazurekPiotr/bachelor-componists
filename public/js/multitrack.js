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
     automaticscroll: true
 });



 showLoading();
 $.get('/api/getSlugsFromProject/' + id, function (response) {
     playlist.load(response);
     playlist.initExporter();
     hideLoading();
 });

 function showLoading() {
     $("#loading").show('slow');
 };

 function hideLoading() {
     $("#loading").finish().hide('slow');
 };



