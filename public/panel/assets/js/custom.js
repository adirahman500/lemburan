function showToast(selectedType, titleText, messageText) {
  const toastPlacementExample = document.querySelector('.toast-placement-ex');
  $("#titleText").html(titleText);
  $("#messageText").html(messageText);
  let selectedPlacement, toastPlacement;
  selectedPlacement = document.querySelector('#selectPlacement').value.split(' ');

  toastPlacementExample.classList.add(selectedType);
  DOMTokenList.prototype.add.apply(toastPlacementExample.classList, selectedPlacement);
  toastPlacement = new bootstrap.Toast(toastPlacementExample);
  toastPlacement.show();
}
function show_loading() {
  var elemenModalLoading = document.getElementsByClassName('modal-loading');
  var ModalBody = document.getElementsByClassName('modal-body');
  for (var i = 0; i < elemenModalLoading.length; i++) {
    elemenModalLoading[i].style.display = "block";
  }
  for (var i = 0; i < ModalBody.length; i++) {
    ModalBody[i].style.pointerEvents = "none";
    ModalBody[i].style.background = 'white';
    ModalBody[i].style.opacity = '0.4';
  }
}
function hide_loading() {
  var elemenModalLoading = document.getElementsByClassName('modal-loading');
  var ModalBody = document.getElementsByClassName('modal-body');
  for (var i = 0; i < elemenModalLoading.length; i++) {
    elemenModalLoading[i].style.display = "none";
  }
  for (var i = 0; i < ModalBody.length; i++) {
    ModalBody[i].style.pointerEvents = "auto";
    ModalBody[i].style.background = "transparent";
    ModalBody[i].style.opacity = '1';
  }
}
function updateClock() {
  var currentTime = new Date();
  var hours = currentTime.getHours();
  var minutes = currentTime.getMinutes();
  var seconds = currentTime.getSeconds();
  hours = (hours < 10 ? "0" : "") + hours;
  minutes = (minutes < 10 ? "0" : "") + minutes;
  seconds = (seconds < 10 ? "0" : "") + seconds;

  var timeString = hours + ":" + minutes + ":" + seconds;
  document.getElementById("clock").innerText = timeString;
}
setInterval(updateClock, 1000);
window.onload = updateClock;