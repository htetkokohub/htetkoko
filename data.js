

function reload() {
  var videoView = ''
  fetch('https://raw.githubusercontent.com/htetkokohub/pronwebmm/main/pronwebdata.json')
  .then(response  => response.json())
  .then(data => {
    for(i=0;i<=5;i++){
      fetch(`http://htetkoko.free.nf/mediafire/directlink.php?url=${data[i].link}`)
      .then(url => url.json())
      .then(link => {
      let video = link;
      let photo = data[i].image;
      let name = data[i].title;
      videoView = videoView + `<div class="bg-dark border border-3 border-light rounded p-2  mb-4  col-md-6">
      <video src="${video}" controls autoplay="false" width="100%" height="230px" class="mb-1 border border-2 border-danger rounded" poster="${photo}"></video>
      <div class="text-light  p-3 border border-2 border-danger rounded m-0">${name}</div>
    </div>`;
      })
    }
    document.getElementById('vdView').innerHTML = videoView;
  })
  .catch(error => {
    alert('Sorry : Error404//contact us => (https://t.me/htetkokotelegram)')
  });
}
