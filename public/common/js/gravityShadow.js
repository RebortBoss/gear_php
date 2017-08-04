/**
 * 使用重力感应来达到动态阴影的效果。
 * 第一步为你的dom元素添加类 gravity_shadow
 * 第二步调用js代码 new GravityShadow() 来启动动态阴影
 * --------------------------------------------------
 * Using gravity sensing to achieve dynamic shadows.
 * Step 1:add class 'gravity_shadow' to your html DOM.
 * Step 2:using js code "new GravityShadow()" to active.
 * --------------------------------------------------
 * @author yuri2 <824831811@qq.com>
 * Created by Yuri2 on 2017/4/1.
 */
function GravityShadow(config) {
    var data={
        x_offset:2,
        y_offset:2,
        z_offset:2,
        color_shadow:'gray',
        class_name:'gravity_shadow',
        default_height:10,
        height_name:'gravity_height',
    };
    var isFirst=true;
    if (typeof (config) =='object'){
        data=jsonMerge(data,config);
    }

    function jsonMerge(jsonobject1, jsonobject2) {
        var resultJsonObject={};
        for(var attr1 in jsonobject1){
            resultJsonObject[attr1]=jsonobject1[attr1];
        }
        for(var attr2 in jsonobject2){
            resultJsonObject[attr2]=jsonobject2[attr2];
        }
        return resultJsonObject;
    }

    start();
    function orientationHandler(event) {
        if (isFirst){
            data.Balpha=event.alpha-data.z_offset;
            data.Bbeta=event.beta-data.x_offset;
            data.Bgamma=event.gamma-data.y_offset;
            isFirst=false;
        }
        $("."+data.class_name).each(function () {
            var height=$(this).attr(data.height_name);
            if (!height){height=data.default_height}
            $(this).css('box-shadow',getCssShadow(event.alpha,event.beta,event.gamma,height));
        })
    }

    function getCssShadow(alpha,beta,gamma,height) {
        var x=(data.Bgamma-gamma)*height*0.05;
        var y=(data.Bbeta-beta)*height*0.05;
        return x+'px '+y+'px 3px '+data.color_shadow;
    }

    function start() {
        if (window.DeviceOrientationEvent) {
            window.addEventListener("deviceorientation", orientationHandler, false);
        } else {
            if (console &&typeof (console)=='function'){
                console.log('Can not use gravity sensor.')
            }
        }
    }
}