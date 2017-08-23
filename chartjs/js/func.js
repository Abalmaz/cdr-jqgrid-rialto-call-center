jQuery(document).ready(function(){
    var lastSel;
    jQuery('#month').jqGrid({
        url:'getdata.php',
        datatype: 'json',
        mtype: 'GET',
        colNames:['Name', 'Quantity', 'Amount ,$', 'Average, $'],
        colModel :[
            {name:'name', index:'name', width: 180, classes:'green'},
            {name:'kol_dep', index:'kol_dep',width: 80},
            {name:'sum_dep', index:'sum_dep', width: 80},
            {name:'average', index:'average', width: 80}
        ],
        pager: ('#pager'),
        rowNum:20,
        rowList:[20,40,60],
        sortname: 'id',
        sortorder: 'asc',
        viewrecords: true,
      	height: 260
      //	,autowidth: true
       // ,shrinkToFit: true

    });
  /* $('.ui-jqgrid-bdiv').css('height', '260 px',  '!important');*/
});

jQuery(document).ready(function(){
    var lastSel;
    jQuery('#month_type').jqGrid({
        url:'getdata_type.php',
        datatype: 'json',
        mtype: 'GET',
        colNames:['Deposites', 'Quantity', 'Amount ,$', 'Average, $'],
        colModel :[
            {name:'type_dep', index:'name',width: 180, classes:'green'},
            {name:'kol_dep', index:'kol_dep', width: 80},
            {name:'sum_dep', index:'sum_dep', width: 80},
            {name:'average', index:'average', width: 80}
        ],
        pager: ('#pager'),
        rowNum:20,
        rowList:[20,40,60],
        sortname: 'id',
        sortorder: 'asc',
        viewrecords: true,
      	//autowidth: true,
        //shrinkToFit: true,
      	height: 78          

    });
});

jQuery(document).ready(function(){
    var lastSel;
    jQuery('#day').jqGrid({
        url:'getdata_day.php',
        datatype: 'json',
        mtype: 'GET',
        colNames:['Name', 'Quantity', 'Amount ,$'],
        colModel :[
            {name:'name', index:'name', width: 180, classes:'green'},
            {name:'kol_dep', index:'kol_dep', width: 80},
            {name:'sum_dep', index:'sum_dep', width: 80}
        ],
        pager: ('#pager'),
        rowNum:20,
        rowList:[20,40,60],
        sortname: 'id',
        sortorder: 'asc',
        viewrecords: true,
       // autowidth: true,
       // shrinkToFit: true,
        height: 260

    });
});


jQuery(document).ready(function(){
    var lastSel;
    jQuery('#day_type').jqGrid({
        url:'getdata_day_type.php',
        datatype: 'json',
        mtype: 'GET',
        colNames:['Deposites', 'Quantity', 'Amount ,$'],
        colModel :[
            {name:'type_dep', index:'name', width: 180, classes:'green'},
            {name:'kol_dep', index:'kol_dep', width: 80},
            {name:'sum_dep', index:'sum_dep', width: 80}
        ],
        pager: ('#pager'),
        rowNum:20,
        rowList:[20,40,60],
        sortname: 'id',
        sortorder: 'asc',
        viewrecords: true,
        //autowidth: true,
       // shrinkToFit: true,
        height: 78

    });
});

setInterval('$("#month").trigger("reloadGrid")',600000); //update every 10 minute
setInterval('$("#day").trigger("reloadGrid")',600000); //update every 10 minute
setInterval('$("#month_type").trigger("reloadGrid")',600000); //update every 10 minute
setInterval('$("#day_type").trigger("reloadGrid")',600000); //update every 10 minute

