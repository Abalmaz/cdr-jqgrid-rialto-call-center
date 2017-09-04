var lastSel;
$(function () {
    jQuery('#grid_user').jqGrid({
        url:'getdata_user.php',
        datatype: 'json',
        mtype: 'GET',
        colNames:['ID', 'Platform', 'Name', 'Number','Date of last phone contact','Date of next contact', 'Task', 'Result',
            'NOTE', '',''],
        colModel :[
            {name:'id_platform', index:'id_platform', width:70,editable:false, search:true },
            {name:'platform', index:'platform', width:65,editable:false, search:true, stype:"select",  searchoptions:{value:":[All];MRT:MRT;RTO:RTOption;XLR:XLR;FX:ForenX;PFX:PFX;10Brokers:10Brokers"}},
            {name:'name', index:'name', width:180,editable:false,  sorttype:'string',search:true, searchoptions:{sopt:['cn','eq','bw','bn','nc','ew','en']}},

            {name:'secret_phone', index:'secret_phone', width:90,editable:false, search:true, sorttype:'number', searchoptions:{sopt:['cn','eq','bw','bn','nc','ew','en']}
                , formatter: function (cellvalue) {
                var phone = cellvalue;
                var cellPrefix = '';
                return cellPrefix + '<a href="#" class = "caller" onclick="call('+ phone +')">'+ phone + '</a>';

            }
            },
            {name:'calldate', index:'calldate', width:80,editable:false, search:false, sorttype:'date', formatter:'date', formatoptions: {
                srcformat: 'ISO8601Long',
                newformat: 'Y-m-d',
                defaultValue:null}}

            ,{name:'date_call', index:'date_call', width:80, editable:true,
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
                },
                search:false, sortable: true,sorttype:'date', formatter:'date', formatoptions: {
                srcformat: 'ISO8601Long',
                newformat: 'Y-m-d',
                defaultValue:null}}
            ,{name:'task', index:'task', width:100,editable:true, search:true, stype:"select",  searchoptions:{value:"call:позвонить;trade:торговать;cancel_wd:отмена вывода"}
            ,edittype:"select" , editoptions:{value:"call:позвонить;trade:торговать;cancel_wd:отмена вывода"}}
            ,{name:'result', index:'result', width:100,editable:true, search:true, stype:"select",  searchoptions:{value:"not done:не выполнено;done:выполнено;in process:в процессе"}
                ,edittype:"select" , editoptions:{value:"not done:не выполнено;done:выполнено;in process:в процессе"}}
            ,{name:'note', index:'note', width:200, editable:true, edittype:'textarea', sorttype:'string',search:true, searchoptions:{sopt:['cn','eq','bw','bn','nc','ew','en']}}
            ,{name:'clients_id', index:'clients_id',editable:true, hidden:true}
            ,{ name: 'act', index: 'act', width: 45, align: 'center', sortable: false, formatter: function(cellvalue, options, rowObject)
                {
                    return '<input style="height:22px;" type="button" class="ui-corner-all ui-icon ui-icon-circle-plus" onclick="new_task('+rowObject[9]+')" />';
                }
            }

        ],
            ondblClickRow: function(row_id){
                if (row_id) {
                    jQuery('#grid_user').jqGrid('restoreRow', lastSel);
                    var date_call = jQuery('#grid_user').jqGrid('getColProp','date_call');
                    var task = jQuery('#grid_user').jqGrid('getColProp','task');
                    date_call.editable = false;
                    task.editable = false;
                    jQuery('#grid_user').editRow(row_id, {keys: true,"mtype": "GET" ,"aftersavefunc" : reload});
                    date_call.editable = true;
                    task.editable = true;
                    lastSel = row_id;
                }
            },
        pager: ('#pager_user'),
        rowNum:60,
        rowList:[60,40,20],
        sortname: 'date_call',
        sortorder: 'desc',
        subGrid : true,
        subGridOptions: {
            "plusicon"  : "ui-icon-triangle-1-e",
            "minusicon" : "ui-icon-triangle-1-s",
            "openicon"  : "ui-icon-arrowreturn-1-e",
            "reloadOnExpand" : false,
            "selectOnExpand" : true
        },
       subGridRowExpanded: function(subgrid_id, row_id) {
           var html =  $("#tmpl").html();
           $.getJSON('detail.php?id='+row_id, function(data) {
               $.each(data, function() {
                   $.each(this, function(index, element){
                        html = html.replace("<#=" + index + "#>", element);
                    });
                   $("#" + subgrid_id).append(html).tabs();
               });
           });

       },
        viewrecords: true,
        editurl: './update_user.php'
       // ,actionsNavOptions: { addformbutton: true }
        ,gridview: true
        ,gridComplete: function() {



            var rowID = $("#grid_user").getDataIDs();

            var today = new Date();
            var dd = today.getDate();
            var mm = today.getMonth()+1;
            var yyyy = today.getFullYear();

            if(dd<10) {
                dd='0'+dd
            }

            if(mm<10) {
                mm='0'+mm
            }

            today=yyyy+'-'+mm+'-'+dd;

            for (var i = 0; i < rowID.length; i++) {
                var next_contact = $("#grid_user").getCell(rowID[i],'date_call');
                if(next_contact != '' && next_contact!="0000-00-00" && next_contact < today)
                {
                    $("#grid_user").jqGrid('setCell', rowID[i], 'date_call', '', 'ui-state-error ui-state-error-text');
                }
                else if  ( next_contact != null  && next_contact > today ) {
                    $("#grid_user").jqGrid('setCell', rowID[i], 'date_call', '', {'background-color':'LightGreen',
                        'background-image':'none'});
                }
                else{
                    $("#grid_user").jqGrid('setCell', rowID[i], 'date_call', '', 'ui-state-highlight');
                }

            }
        }

    });
    jQuery("#grid_user").jqGrid('navGrid',"#pager_user",{edit:false,add:false,del:false},
        {},
        {},
        {}
    );
    jQuery("#grid_user").jqGrid('inlineNav',"#pager_user", parameters);
    jQuery("#grid_user").jqGrid('filterToolbar',{
        stringResult: true,
        searchOperators: true});


});


function new_task(client_id)
{
    jQuery("#grid_user").jqGrid('editGridRow',"new", {beforeShowForm: function (form) {
        $('#tr_result',form).hide();
        $('#tr_note',form).hide();
        }, width: 250, top:300, left: 700,mtype: "GET", url:'./update_user.php?client_id='+client_id, closeAfterAdd:true});

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

function reload() {
    $("#grid_user").trigger("reloadGrid");
}

