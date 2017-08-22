 var id = document.getElementById('projectId').dataset.projectId;
 var links;


 var playlist = WaveformPlaylist.init({
     samplesPerPixel: 2000,
     mono: true,
     waveHeight: 200,
     container: document.getElementById('playlist'),
     state: 'cursor',
     colors: {
         waveOutlineColor: 'black',
         timeColor: 'white',
         fadeColor: 'blue'
     },
     controls: {
         show: true,
         width: 100
     },
     zoomLevels: [500, 1000, 2000, 3000, 5000],
     automaticscroll: true
 });




 $.get('/api/getSlugsFromProject/' + id, function (response) {
     console.log(response);
     playlist.load(response);
     playlist.initExporter();
 });



