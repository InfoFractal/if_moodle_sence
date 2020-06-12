require(['jquery'], function($) {
    $("#form-sence").submit(function(){
        var vals = [];
        var form = document.getElementById('form-sence');
        if(form.CodigoCurso.value == "-1"){
            vals.push("Debes seleccionar un curso");
        }
        if(vals.length>0){
            var i;
            var text = "";
            for ( i = 0; i < vals.length; i++) {
                text += vals[i]+"\n";
            }
            alert(text);
            return false;
        }else{
            
        }
    });
    $("#course-selector").change(function(){
        if($("#course-selector").val()!="-1"){
            var id = $("#course-selector").val();
            var url = document.location.hostname;
            $.ajax({
                url: "/moodle/blocks/if_sence_login/model/block_if_sence_login.php",
                type: "POST",
                data: {id:id}
            }).done(function(js) {
                eval(js);
                //console.log(js);
                $("#submit-button").attr("disabled", false);
            });
            
        }else{
            $("#submit-button").attr("disabled", true);
        }
    });
});