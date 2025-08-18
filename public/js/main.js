var playButton = document.getElementById("play_button");
// Event listener for the play/pause button
playButton.addEventListener("click", function() {
if (video.paused == true) {
// Play the video
video.play();
// Update the button text to 'Pause'
playButton.innerHTML = '<i class="fa-solid fa-pause"></i>';
} else {
// Pause the video
video.pause();
// Update the button text to 'Play'
playButton.innerHTML = '<i class="fa-solid fa-play"></i>';
}
});


//  testimonial slider
$('.testimonial-slider').slick({
    dots: true,
infinite: true,
autoplay: true,
  autoplaySpeed: 2000,
slidesToShow: 1,
centerMode: true,
centerPadding: '60px',
arrows: false,
responsive: [
    {
      breakpoint: 1024,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 3,
        infinite: true,
        dots: true
      }
    },
    {
      breakpoint: 600,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 2
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
    }
    // You can unslick at a given breakpoint now by adding:
    // settings: "unslick"
    // instead of a settings object
  ]
});