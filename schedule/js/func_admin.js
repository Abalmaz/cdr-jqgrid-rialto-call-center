var lastSel;
$(function () {
    jQuery('#grid_admin').jqGrid({
        url:'getdata_admin.php',
        datatype: 'json',
        mtype: 'GET',
        colNames:['ID', 'Platform', 'Name', 'Number','Date of last phone contact','Date of next contact','Trader', 'Task', 'Result',
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

            ,{name:'date_call', index:'date_call', width:75, editable:true,
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
                search:true,
                searchoptions:{
                    sopt: ['eq', 'ne'],
                    dataInit:function(el){
                        $(el).datepicker({firstDay: 1,dateFormat:'yy-mm-dd'})
                            .change(function() {
                                $("#grid_admin")[0].triggerToolbar();
                            });
                    },
                    defaultValue: null
                }, stype:"date",
                sortable: true,sorttype:'date', formatter:'date', formatoptions: {
                    srcformat: 'ISO8601Long',
                    newformat: 'Y-m-d',
                    defaultValue:null}}
            ,{name:'trader', index:'users.name', width:100,editable:false,  edittype:"select",
                editoptions:{value:":;Askerova:Askerova;Mazurok:Mazurok;Vinnichenko:Vinnichenko;Ostrovaya:Ostrovaya"} ,
                search:true,stype:"select", searchoptions:{sopt: ['cn', 'nu'],value:":;Askerova:Askerova;Mazurok:Mazurok;Vinnichenko:Vinnichenko;Ostrovaya:Ostrovaya"}}
            ,{name:'task', index:'task', width:100,editable:true, search:true, stype:"select",  searchoptions:{value:":;call:позвонить;trade:торговать;cancel_wd:отмена вывода"}
                ,edittype:"select" , editoptions:{value:":;call:позвонить;trade:торговать;cancel_wd:отмена вывода"}}
            ,{name:'result', index:'result', width:100,editable:false, search:true, stype:"select",  searchoptions:{value:":;not done:не выполнено;done:выполнено;in process:в процессе"}
                ,edittype:"select" , editoptions:{value:":;not done:не выполнено;done:выполнено;in process:в процессе"}}
            ,{name:'note', index:'note', width:200, editable:false, edittype:'textarea', sorttype:'string',search:true, searchoptions:{sopt:['cn','eq','bw','bn','nc','ew','en']}}
            ,{name:'team', index:'team',editable:false, hidden:true}
            ,{ name: 'act', index: 'act', width: 45, align: 'center', sortable: false,editable:false, search:false, formatter: function(cellvalue, options, rowObject)
            {
                return '<input style="height:22px;" type="button" class="ui-corner-all ui-icon ui-icon-circle-plus" onclick="new_task('+options.rowId+','+rowObject[10]+')" />';
            }
            }

        ],
        pager: ('#pager_admin'),
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
        editurl: './update_admin.php'
        // ,actionsNavOptions: { addformbutton: true }
        ,gridview: true
        ,gridComplete: function() {



            var rowID = $("#grid_admin").getDataIDs();

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
                var next_contact = $("#grid_admin").getCell(rowID[i],'date_call');
                if(next_contact != '' && next_contact!="0000-00-00" && next_contact < today)
                {
                    $("#grid_admin").jqGrid('setCell', rowID[i], 'date_call', '', 'ui-state-error ui-state-error-text');
                }

            }
        }

    });
    jQuery("#grid_admin").jqGrid('navGrid',"#pager_admin",{edit:false,add:false,del:false},
        {},
        {},
        {}
    );
    //jQuery("#grid_admin").jqGrid('inlineNav',"#pager_admin", parameters);
    jQuery("#grid_admin").jqGrid('filterToolbar',{
        stringResult: true,
        searchOperators: true});


});


function new_task(client_id, team)
{
    jQuery("#grid_admin").jqGrid('editGridRow',"new", {beforeShowForm: function (form) {
    }, width: 250, top:300, left: 700,mtype: "GET", url:'./update_admin.php?clients_id='+client_id+'&team='+team, closeAfterAdd:true});

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
    $("#grid_admin").trigger("reloadGrid");
}

