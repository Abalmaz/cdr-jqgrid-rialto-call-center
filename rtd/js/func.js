jQuery(document).ready(function(){
    var lastSel;
   // var today = new Date();
    jQuery('#grid').jqGrid({
        url:'getdata.php',
        datatype: 'json',
        mtype: 'GET',
        colNames:['ID', 'Platform', 'Name', 'Note','Number', 'Secret phone','E-mail','Team of Client','Support status','Date of last phone contact','Date of last Skype contact','Account in Skype','Date of next contact','Date of ftd',
        'FTD, $', 'Date of last RTD','Last RTD, $', 'Total RTD, $','NOTE', 'Average income', 'Field of activity', 'Kind of activity', 'Documents',''],
        colModel :[
            {name:'id_platform', index:'id_platform', width:70,editable:false, search:true },
            {name:'platform', index:'platform', width:55,editable:false, search:true, stype:"select",  searchoptions:{value:":[All];MRT:MRT;RTO:RTOption;XLR:XLR;FX:ForenX;PRFX:PRFX;PFX:PFX;10Brokers:10Brokers"}},
            {name:'name', index:'name', width:180,editable:false,  sorttype:'string',search:true, searchoptions:{sopt:['cn','eq','bw','bn','nc','ew','en']}},
            {name:'note', index:'note', width:200, editable:true, edittype:'textarea', sorttype:'string',search:true, searchoptions:{sopt:['cn','eq','bw','bn','nc','ew','en']}},
            {name:'phone', index:'phone', width:110,editable:false, search:true, sorttype:'number', searchoptions:{sopt:['cn','eq','bw','bn','nc','ew','en']}
             , formatter: function (cellvalue, options, rowObject) {
                    var phone = cellvalue;
                    var cellPrefix = '';
                    return cellPrefix + '<a href="#" class = "caller" onclick="call('+ phone +')">'+ phone + '</a>';

                }
            },
            {name:'secret_phone', index:'secret_phone', width:110,editable:false, search:true, sorttype:'number', searchoptions:{sopt:['cn','eq','bw','bn','nc','ew','en']}
            , formatter: function (cellvalue, options, rowObject) {
                    var phone = cellvalue;
                    var cellPrefix = '';
                    return cellPrefix + '<a href="#" class = "caller" onclick="call('+ phone +')">'+ phone + '</a>';

                }
            },
            {name:'mail', index:'mail', width:200,editable:false, search:true,  sorttype:'string', searchoptions:{sopt:['cn','eq','bw','bn','nc','ew','en']}},
            {name:'team', index:'team', width:55,editable:true,  edittype:"select", editoptions:{value:":;1:Askerova;2:Mazurok;3:Vinnichenko;4:Ostrovaya;666:кладбище"} , search:true,stype:"select", searchoptions:{sopt: ['cn', 'nu'],value:": ;1:Askerova;2:Mazurok;3:Vinnichenko;4:Ostrovaya;666:кладбище"}},
            {name:'status', index:'status', width:70, editable:true, edittype:"select", editoptions:{value:":;1:торгует;2:не торгует;4:еще не торговал;3:мертв"}, search:true, stype:"select", searchoptions:{value:":;1:торгует;2:не торгует;4:еще не торговал;3:мертв"}},
            {name:'calldate', index:'calldate', width:80,editable:false, search:false, sorttype:'date', formatter:'date', formatoptions: {
                srcformat: 'ISO8601Long',
                newformat: 'Y-m-d',
                defaultValue:null}}
            ,{name:'skype_date', index:'skype_date', width:80, editable:true,  search:false, sorttype:'date', formatter:'date',formatoptions: {
                srcformat: 'ISO8601Long',
                newformat: 'Y-m-d'
            },
                editoptions:{size:20,
                    dataInit:function(el){
                        $(el).datepicker({firstDay: 1,dateFormat:'yy-mm-dd'});
                    },
                    defaultValue: function(){
                        var currentTime = new Date();
                        var month = parseInt(currentTime.getMonth() + 1);
                        month = month <= 9 ? "0"+month : month;
                        var day = currentTime.getDate();
                        day = day <= 9 ? "0"+day : day;
                        var year = currentTime.getFullYear();
                        return year+"-"+month + "-"+day;
                    }
                }},
           {name:'skype_name', index:'skype_name', width:180, editable:true, edittype:'textarea', sorttype:'string',search:true, searchoptions:{sopt:['cn','eq','bw','bn','nc','ew','en']}}
            ,{name:'date_contact', index:'date_contact', width:80, editable:true,  search:false, sortable: true,
                                                                                                            sorttype:'date',/* formatter:'date',formatoptions: {
                                                                                                                srcformat: 'ISO8601Long',
                                                                                                                newformat: 'Y-m-d'
                                                                                                            },*/
                                                                                                            editoptions:{size:20,
                                                                                                            dataInit:function(el){
                                                                                                                $(el).datepicker({firstDay: 1,dateFormat:'yy-mm-dd'});
                                                                                                            },
                                                                                                            defaultValue: function(){
                                                                                                                var currentTime = new Date();
                                                                                                                var month = parseInt(currentTime.getMonth() + 1);
                                                                                                                month = month <= 9 ? "0"+month : month;
                                                                                                                var day = currentTime.getDate();
                                                                                                                day = day <= 9 ? "0"+day : day;
                                                                                                                var year = currentTime.getFullYear();
                                                                                                                return year+"-"+month + "-"+day;
                                                                                                            }
                                                                                                        } }
            ,{name:'date_ftd', index:'date_ftd', width:80, editable:false, sorttype:'date', formatter:'date',formatoptions: {
                                                                                                                 srcformat: 'ISO8601Long',
                                                                                                                 newformat: 'Y-m-d',
                                                                                                                 defaultValue:null
                                                                                                                 }}
            ,{name:'amount_ftd', index:'amount_ftd', width:70, editable:false, search:true, sorttype:'integer', searchoptions:{sopt:['eq','ne','le','lt','gt','ge']}}
            ,{name:'date_rtd', index:'date_rtd', width:80, editable:false,  search:false, sorttype:'date', formatter:'date',formatoptions: {
                                                                                                                  srcformat: 'ISO8601Long',
                                                                                                                   newformat: 'Y-m-d',
                                                                                                                   defaultValue:null
                                                                                                                   }}
            ,{name:'last_rtd', index:'last_rtd', width:70,  editable:false, search:false, sorttype:'integer', searchoptions:{sopt:['eq','ne','le','lt','gt','ge']}}
            ,{name:'sum_trd', index:'sum_rtd', width:70,  editable:false, search:false, sorttype:'integer', searchoptions:{sopt:['eq','ne','le','lt','gt','ge']}}
            ,{name:'birthday', index:'birthday', width:75,editable:true, search:false,
               formatter:'date', formatoptions: {
                srcformat: 'ISO8601Long',
                    newformat: 'd.m.Y'}}
            ,{name:'avg_income', index:'avg_income', width:70, editable:true, search:true, sorttype:'integer', searchoptions:{sopt:['eq','ne','le','lt','gt','ge']}}
            ,{name:'field_activity', index:'field_activity', width:200, editable:true, edittype:'textarea', sorttype:'string',search:true, searchoptions:{sopt:['cn','eq','bw','bn','nc','ew','en']}}
            ,{name:'kind_activity', index:'kind_activity', width:70, editable:true, edittype:"select", editoptions:{value:":;SPD:SPD;employee:employee"}, search:true, stype:"select", searchoptions:{value:":;SPD:SPD;employee:employee"}}
            ,{name:'documents', index:'documents', width:120, stype:"select",editable:true, edittype:"select", editoptions:
           {value:":;all doc:all doc;no dod:no dod;no por:no por;no poi: no poi;no quality:no quality;no dod, no por:no dod, no por;no CC, no dod:no CC, no dod;no CC, no dod, no por:no CC, no dod, no por; only CC:only CC;no doc:no doc;no poi, no por:no poi, no por;no CC, no poi, no dod:no CC, no poi, no dod;no CC, no poi, no por:no CC, no poi, no por"},
           searchoptions:{sopt:['eq','ne'],value:":;all doc:all doc;no dod:no dod;no por:no por;no poi: no poi;no quality:no quality;no dod, no por:no dod, no por;no CC, no dod:no CC, no dod;no CC, no dod, no por:no CC, no dod, no por; only CC:only CC;no doc:no doc;no poi, no por:no poi, no por;no CC, no poi, no dod:no CC, no poi, no dod;no CC, no poi, no por:no CC, no poi, no por"}}
          ,{name: 'myac', width:55, sortable:false,  search:false, formatter:'actions',
                formatoptions:{keys:true,delbutton:false}}
        ],
      	ondblClickRow: function(row_id){
            if (row_id) {
                jQuery('#grid').jqGrid('restoreRow', lastSel);
                jQuery('#grid').editRow(row_id, true);
                lastSel = row_id;
            }
        },
        pager: ('#pager'),
        rowNum:60,
        rowList:[60,40,20],
        sortname: 'date_contact',
        sortorder: 'desc',
        viewrecords: true,
        editurl: './update.php'
        ,gridComplete: function() {
            var rowID = $("#grid").getDataIDs();
            var today = new Date();
            var dd = today.getDate();
            var mm = today.getMonth()+1; //January is 0!
            var yyyy = today.getFullYear();

            if(dd<10) {
                dd='0'+dd
            }

            if(mm<10) {
                mm='0'+mm
            }

            today=yyyy+'-'+mm+'-'+dd;

            for (var i = 0; i < rowID.length; i++) {
                var next_contact = $("#grid").getCell(rowID[i],'date_contact');
                if(next_contact != '' && next_contact!="0000-00-00" && next_contact <= today)
                 {
                    $("#grid").jqGrid('setCell', rowID[i], 'date_contact', '', 'ui-state-error ui-state-error-text');
                }
                else if  ( next_contact != null  && next_contact > today ) {
                   $("#grid").jqGrid('setCell', rowID[i], 'date_contact', '', {'background-color':'LightGreen',
                        'background-image':'none'});
                }
                else{
                    $("#grid").jqGrid('setCell', rowID[i], 'date_contact', '', 'ui-state-highlight');
                }
              var doc = $("#grid").getCell(rowID[i],'documents');
                if(doc == 'no doc')
                {
                    $("#grid").jqGrid('setCell', rowID[i], 'documents', '', 'ui-state-error ui-state-error-text');
                }
                else if  ( doc == 'all doc' ) {
                    $("#grid").jqGrid('setCell', rowID[i], 'documents', '', {'background-color':'LightGreen',
                        'background-image':'none'});
                }
                else{
                    $("#grid").jqGrid('setCell', rowID[i], 'documents', '', 'ui-state-highlight');
                }
            }
        }

    });
    jQuery("#grid").jqGrid('navGrid',"#pager",{edit:false,add:false,del:true},
        {},
        {},
        {}
      // ,{multipleSearch:true, multipleGroup:true}
    );
    jQuery("#grid").jqGrid('inlineNav',"#pager");
    jQuery("#grid").jqGrid('filterToolbar',{
        stringResult: true,
        searchOperators: true});
  /* jQuery("#grid").jqGrid('navButtonAdd','#pager',{
        caption:"Export to Excel",
        onClickButton : function () {
            exportExcel();
                   }
    });*/
/*    $('.ui-jqgrid-bdiv').css('height', '600 px');*/


});

function rowColorFormatter(cellValue, options, rowObject) {
    if (cellValue == "True")
        rowsToColor[rowsToColor.length] = options.rowId;
    return cellValue;
}

datePick = function(elem)
{
    jQuery(elem).datepicker();
}
function exportExcel()
{
    var keys=[], ii=0, rows="";
    var ids=$("#grid").getDataIDs();
    var row=$("#grid").getRowData(ids[0]);
    var p = $("#grid").jqGrid("getGridParam");
    var rows="<table><thead><tr>";
    for (var k in row) {

        rows=rows+'<th>'+p.colNames[ii]+'</th>'; //[ii+1]
        keys[ii++]=k;
    }
    rows=rows+"</tr></thead><tbody>";
    for(i=0;i<ids.length;i++) {
        row=$("#grid").getRowData(ids[i]);
        rows=rows+"<tr>";
        for(j=0;j<keys.length;j++){
            rows=rows+'<td>'+row[keys[j]]+'</td>';
        }
        rows=rows+"</tr></tbody>";
    }
    rows=rows+"</table>";
    document.getElementById('csvBuffer').value=rows;
    document.getElementById('_export').submit();
}

function gridReloadA(){
jQuery("#grid").jqGrid('setGridParam', {url:"getdata.php?status=2"}).trigger("reloadGrid");
}

function call(phone) {
    var elem = $(this);
    $.ajax({
        type: "GET",
        url: "./call.php",
        data: "phone=" + phone,
        dataType:"json"
    });
    return false;
}
