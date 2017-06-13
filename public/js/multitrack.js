 var id = document.getElementById('projectId').dataset.projectId;
 var links;


 var playlist = WaveformPlaylist.init({
     samplesPerPixel: 3000,
     mono: true,
     waveHeight: 100,
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
     zoomLevels: [500, 1000, 3000, 10000]
 });




 $.get('/api/getSlugsFromProject/' + id, function (response) {
     var stuff = response.replace(/\"link\"/g, "\"src\"");
     var step = stuff.replace(/\\/g, "");
     links = step.replace(/ /g, "+");
     playlist.load(JSON.parse(links));
     playlist.initExporter();
 });



