function getRemainingCat() {
    if (typeof(callCheck) == 'undefined') {
        callCheck = 'yes';
        $.ajax({
            url: "ajax/getcat.php"
        }).done(function( msg ) {
            $("#maincatrow").append(msg);
            $('.extcat').show('slow');
            $('#allcatbtncon').hide();
        });
    }
}

function setHeights(id1, id2) {
    if (document.getElementById(id1).style.maxHeight == '9999px') { 
        document.getElementById(id1).style.maxHeight='0px';
        document.getElementById(id2).classList.add("glyphicon-plus");
        document.getElementById(id2).classList.remove("glyphicon-minus");
    } else {
        document.getElementById(id1).style.maxHeight='9999px';
        document.getElementById(id2).classList.remove("glyphicon-plus");
        document.getElementById(id2).classList.add("glyphicon-minus");
    }
}

function setWhyCertifiedHeight(id1, id2) {
    if (document.getElementById(id1).style.maxHeight == '9999px') { 
        document.getElementById(id1).style.maxHeight='0px';
        document.getElementById(id2).classList.add("wgcactive");
        document.getElementById(id2).classList.remove("wgcactive");
    } else {
        document.getElementById(id1).style.maxHeight='9999px';
        document.getElementById(id2).classList.remove("wgcactive");
        document.getElementById(id2).classList.add("wgcactive");
    }
}

function showRemainingCoursesFree() {
    $.ajax({
        url: "/ajax/general.php?task=show_free_courses"
    }).done(function( msg ) {
        $("#showfree").append(msg);
        $("#showfreebtn").hide();
    });
}

function submitCourseForm(eid, val) {
    document.getElementById(eid).value = val;
    document.filterFrm.submit();
}

function keepOnlyCheckbox(valueToKeep) {
    const checkboxes = document.querySelectorAll('input[name="type[]"]');
    checkboxes.forEach(function(cb) {
        if (cb.value == valueToKeep) {
            cb.checked = true;
        } else {
            cb.checked = false;
        }
    });
    submitCourseForm('pagenum', 1);
}
