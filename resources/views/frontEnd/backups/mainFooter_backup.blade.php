<!-- Footer -->
<footer class="main-footer-header2">
    <strong>Copyright © 2018 -<a href="https://samybot.com">Samy Technologies inc.</a>.</strong> All rights reserved.
</footer>
<!-- Footer -->
</body>
<script src="https://www.jqueryscript.net/demo/Animated-Circular-Progress-Bar-with-jQuery-Canvas-Circle-Progress/dist/circle-progress.js"></script>
<script>
    $(document).ready(function ($) {
        function animateElements() {
            $('.progressbar').each(function () {
                var elementPos = $(this).offset().top;
                var topOfWindow = $(window).scrollTop();
                var percent = $(this).find('.circle').attr('data-percent');
                var percentage = parseInt(percent, 10) / parseInt(100, 10);
                var animate = $(this).data('animate');
                if (elementPos < topOfWindow + $(window).height() - 30 && !animate) {
                    $(this).data('animate', true);
                    $(this).find('.circle').circleProgress({
                        startAngle: -Math.PI / 2,
                        value: percent / 100,
                        thickness: 6,
                        fill: {
                            color: '#b82c1e'
                        }
                    }).on('circle-animation-progress', function (event, progress, stepValue) {
//                        $(this).find('div').text((stepValue*100).toFixed(1) + "%");
                    }).stop();
                }
            });
        }

        // Show animated elements
        animateElements();
        $(window).scroll(animateElements);
    });
</script>
</html>