jQuery(document).ready(function(){
    // var lastSel;
    // var today = new Date();
    jQuery('#grid').jqGrid({
        url:'getdata.php',
        datatype: 'json',
        mtype: 'GET',
        colNames:['ID', 'Name', 'Number','E-mail', 'Comments', 'Date of registration','Language', 'Status', 'Last call', 'Count fail disposition', 'Source'],
        colModel :[
            {name:'uid', index:'uid', width:80,editable:false, search:true },

            {name:'name', index:'name', width:250,editable:false,  sorttype:'string', search:true, searchoptions:{sopt:['cn','eq','bw','bn','nc','ew','en']}},

            {name:'phone', index:'phone', width:150,editable:false, search:true, sorttype:'number', searchoptions:{sopt:['cn','eq','bw','bn','nc','ew','en']}},
            {name:'email', index:'email', width:220,editable:false, search:false,  sorttype:'string', searchoptions:{sopt:['nc','eq','bw','bn','cn','ew','en']}},
            {name:'comments', index:'comments', width:300, editable:true, edittype:'textarea', sorttype:'string',search:true, searchoptions:{sopt:['cn','eq','bw','bn','nc','ew','en']}},
            {name:'date_reg', index:'date_reg', width:80,editable:false, search:false, sorttype:'date', formatter:'date', formatoptions: {
                srcformat: 'ISO8601Long',
                newformat: 'Y-m-d',
                defaultValue:null}}
            ,{name:'language', index:'language', width:70, editable:false, sorttype:'string',search:true, stype:"select",  searchoptions:{value:":[All];RUS:RUS;ENG:ENG"}}
           ,{name:'status', index:'status', width:100, editable:true,edittype:'select',editoptions:{value:":;not processed:not processed;in processing:in processing;accepted:accepted;rejected:rejected"}, 
             sorttype:'string',search:true, stype:"select",  searchoptions:{value:":[All];not processed:not processed;in processing:in processing;accepted:accepted;rejected:rejected"}}
           , {name:'last_call', index:'last_call', width:80,editable:false, search:false, sorttype:'date', formatter:'date', formatoptions: {
                srcformat: 'ISO8601Long',
                newformat: 'Y-m-d',
                defaultValue:null}}
             , {name:'count_fail', index:'count_fail', width:40,editable:false, search:true }
          ,{name:'id_transaction', index:'id_transaction', width:70, editable:false, sorttype:'string',search:false, stype:"select",  searchoptions:{value:":[All];affiliate:affiliate;'': "}}
        ],
        pager: ('#pager'),
        rowNum:20,
        rowList:[20,40,60],
        sortname: 'uid',
        sortorder: 'asc',
        viewrecords: true,
        editurl: './update.php',
        gridComplete: function() {
            var rowID = $("#grid").getDataIDs();
            for (var i = 0; i < rowID.length; i++) {
                var status = $("#grid").getCell(rowID[i],'status');
                var count = $("#grid").getCell(rowID[i],'count_fail');

               if(status == "rejected" && count >=3)
                {
                    $("#grid").jqGrid('setRowData', rowID[i], false, {'background-color':'rgb(255, 120, 120)',
                        'background-image':'none'});
                }
               else if  ( status == "rejected" && count <3) {
                   $("#grid").jqGrid('setRowData', rowID[i], false, 'ui-state-highlight');
               }
                else if  ( status == "accepted") {
                    $("#grid").jqGrid('setRowData', rowID[i], false, {'background-color':'LightGreen',
                        'background-image':'none'});
                }
            }
        }
    });
    jQuery("#grid").jqGrid('navGrid',"#pager",{edit:false,add:false,del:false},
        {},
        {},
        {}
        // ,{multipleSearch:true, multipleGroup:true}
    );
    jQuery("#grid").jqGrid('inlineNav',"#pager");
    jQuery("#grid").jqGrid('filterToolbar',{
        stringResult: true,
        searchOperators: true});
    jQuery("#grid").jqGrid('navButtonAdd','#pager',{
        caption:"Export to Excel",
        onClickButton : function () {
            exportExcel();
        }
    });
    /*    $('.ui-jqgrid-bdiv').css('height', '600 px');*/


});
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
