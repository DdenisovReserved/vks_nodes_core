function SearchRender() {

}
SearchRender.prototype.renderVks = function(vksObject, vkstype) { //0 = ws, 1 == nws
    var result = "<div class='searched-vks'>";
    //result += !vkstype ? "<span class='label label-success'>ВКС с администратором</span> ":
    //    "<span class='label label-default'>ВКС в ВК</span> ";
    result += "<b>Тема:</b> " + vksObject.title;
    result += "<br><b>Дата/время:</b> " + vksObject.humanized.date + " c " + vksObject.humanized.startTime + " до "+ vksObject.humanized.endTime;
    var link = vkstype ? "?route=VksNoSupport/show/" + vksObject.id : "?route=Vks/show/" + vksObject.id
    result += "<br><a href='" + link + "'><span class='glyphicon glyphicon-link'></span> На страницу ВКС</a>";
    result += "</div>";
    return result;
}
