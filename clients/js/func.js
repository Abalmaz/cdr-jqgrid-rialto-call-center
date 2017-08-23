function secret(value, colname) {
    if (value.length != 9  && value.length !=0)
        return [false,"Secret phone must be 9 symbol"];
    else
        return [true,""];
}

jQuery(document).ready(function(){
    var lastSel;
    jQuery('#grid').jqGrid({
        url:'getdata.php',
        datatype: 'json',
        mtype: 'GET',
        colNames:['ID', 'Platform', 'Name', 'Phone', 'Second phone','Secret phone','E-mail', 'Birthday','Country','Team of Client','Support status','Leads name', 'Documents'],
        colModel :[
           	{name:'id_platform', index:'id_platform', width:80,editable:true, search:true },
            {name:'platform', index:'platform', width:70, search:true, stype:"select",editable:true, edittype:"select", editoptions:{value:":;FX:ForenX;MRT:MRT;RTO:RTOption;XLR:XLR;PRFX:PRFX;PFX:PFX;10Brokers:10Brokers"},
                searchoptions:{value:":[All];MRT:MRT;RTO:RTOption;XLR:XLR;FX:ForenX;PRFX:PRFX;PFX:PFX;10Brokers:10Brokers"}},
           
            {name:'name', index:'name', width:180,editable:true,  sorttype:'string',search:true, searchoptions:{sopt:['cn','eq','bw','bn','nc','ew','en']}},
            {name:'phone', index:'phone', width:110,editable:true, search:true, sorttype:'number', searchoptions:{sopt:['cn','eq','bw','bn','nc','ew','en']}},
            {name:'second_phone', index:'second_phone', width:110, editable:true, search:true, sorttype:'number', searchoptions:{sopt:['cn','eq','bw','bn','nc','ew','en']}},
            {name:'secret_phone', index:'secret_phone', width:110,
             //editable:true, editrules:{custom:true, custom_func:secret},
                search:true, sorttype:'number', searchoptions:{sopt:['cn','eq','bw','bn','nc','ew','en']}},
            {name:'mail', index:'mail', width:200,editable:true, search:true,  sorttype:'string', searchoptions:{sopt:['cn','eq','bw','bn','nc','ew','en']}},
            {name:'birthday', index:'birthday', width:75,editable:true, search:false,
               formatter:'date', formatoptions: {
                srcformat: 'ISO8601Long',
                    newformat: 'd.m.Y'}},
            {name:'country', index:'country', width:140,editable:false,  sorttype:'string',search:true, searchoptions:{sopt:['eq','bw','bn','cn','nc','ew','en']}},
            {name:'team', index:'team', width:65,editable:true,  search:true,stype:"select", searchoptions:{value:":[All];1:Askerova;2:Mazurok;3:Vinnichenko;4:Ostrovaya;666:кладбище"},edittype:"select", editoptions:{value:":;1:Askerova;2:Mazurok;3:Vinnichenko;4:Ostrovaya;666:кладбище"}},
            {name:'status', index:'status', width:65,editable:true,  search:true, stype:"select",  searchoptions:{value:":;1:торгует;2:не торгует;4:еще не торговал;3:мертв"},edittype:"select", editoptions:{value:":;1:торгует;2:не торгует;4:еще не торговал;3:мертв"}},
            {name:'lead_name', index:'lead_name', width:200,editable:true,  sorttype:'string',search:true, searchoptions:{sopt:['eq','bw','bn','cn','nc','ew','en']}},
          {name:'documents', index:'documents', width:140, search:true, stype:"select",editable:true, edittype:"select", editoptions:
           {value:":;all doc:all doc;no dod:no dod;no por:no por;no poi: no poi;no quality:no quality;no dod, no por:no dod, no por;no CC, no dod:no CC, no dod;no CC, no dod, no por:no CC, no dod, no por; only CC:only CC;no doc:no doc;no poi, no por:no poi, no por;no CC, no poi, no dod:no CC, no poi, no dod;no CC, no poi, no por:no CC, no poi, no por"},
           searchoptions:{value:":;all doc:all doc;no dod:no dod;no por:no por;no poi: no poi;no quality:no quality;no dod, no por:no dod, no por;no CC, no dod:no CC, no dod;no CC, no dod, no por:no CC, no dod, no por; only CC:only CC;no doc:no doc;no poi, no por:no poi, no por;no CC, no poi, no dod:no CC, no poi, no dod;no CC, no poi, no por:no CC, no poi, no por"}}
        ],
      ondblClickRow: function(row_id){
            if (row_id) {
                jQuery('#grid').jqGrid('restoreRow', lastSel);
                jQuery('#grid').editRow(row_id, true);
                lastSel = row_id;
            }
        },
        pager: ('#pager'),
        rowNum:20,
        rowList:[20,40,60],
        sortname: 'id',
        sortorder: 'asc',
        viewrecords: true,
        editurl: './update.php',
        gridComplete: function() {
            var rowID = $("#grid").getDataIDs();
            for (var i = 0; i < rowID.length; i++) {
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
   /* $('.ui-jqgrid-bdiv').css('height', '800 px');*/

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