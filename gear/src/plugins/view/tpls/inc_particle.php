<!--背景粒子特效-->
<script type="text/javascript">
    window.onload = function () {
        var config = {
            vx: 4,
            vy: 4,
            height: 2,
            width: 2,
            count: 88,
            color: "133, 133, 133",
            stroke: "177,177,177",
            dist: 6000,
            e_dist: 20000,
            max_conn: 10
        };
        CanvasParticle(config);
    }
</script>
<script type="text/javascript" src="__PUBLIC__/common/js/canvas-particle.js"></script>