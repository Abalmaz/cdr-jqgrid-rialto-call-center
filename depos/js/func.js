jQuery(document).ready(function(){
    var lastSel;
    jQuery('#grid').jqGrid({
        url:'getdata.php',
        datatype: 'json',
        mtype: 'GET',
        colNames:['ID', 'Date', 'Type of Deposits', 'Amount','Junior','Senior','Platform','Client', 'lead_name', 'clients_id'],
        colModel :[
          {name:'id_platform', index:'id_platform', search:true ,width:88, editable:true,  editoptions:{dataInit: function(e) {
                                                                                                    $(e).autocomplete({

                                                                                                        source: "ac_client.php",
                                                                                                        focus: function(event, ui) {
                                                                                                            event.preventDefault();
                                                                                                            $(this).val(ui.item.label);
                                                                                                            return false;
                                                                                                        },
                                                                                                        select: function(event, ui) {
                                                                                                            var rowId = $("#grid").jqGrid('getGridParam', 'selrow');
                                                                                                            if (ui.item) {
                                                                                                                var idPrefix = "#" + rowId + "_", item = ui.item;
                                                                                                                $(idPrefix + "platform").val(item.platform);
                                                                                                                $(idPrefix + "name").val(item.name);
                                                                                                                $(idPrefix + "clients_id").val(item.id);
                                                                                                            }
                                                                                                        }
                                                                                                        });
                                                                                                        }

                                                                                                    }
            },
           {name:'date', index:'date', width:75, search:true ,editable:true, sorttype: 'date',
                formatter: 'date',
                formatoptions: { newformat: 'Y-m-d' },
                jsonmap: function (element) {
                    var d = new Date(parseInt(element.CreatedDate.substr(6)));
                    return d.getFullYear() + "-" + (d.getMonth() + 1) + "-" + d.getDate();
                },
            editoptions:{size:20,
                    dataInit:function(el){
                        $(el).datepicker({dateFormat:'yy-mm-dd'});
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
                searchoptions: {
                    sopt: ['eq', 'ne'],
                    dataInit: function (element) {
                        var self = this;
                        $(element).datepicker({
                            autoclose: true,
                            format: 'yy-mm-dd',
                            dateFormat:'yy-mm-dd',
                            orientation: 'bottom',
                            showOn: 'focus',
                            onSelect: function () {
                                if (this.id.substr(0, 3) === "gs_") {
                                    setTimeout(function () {
                                        self.triggerToolbar();
                                    }, 50);
                                } else {

                                    $(this).trigger('change');
                                }
                            }
                        });
                    }
                }

                                                            }
            ,{name:'type_dep', index:'type_dep', search:true,width:55, editable:true, edittype:"select", stype:"select", editoptions:{value:"FTD:FTD;RTD:RTD;CHBK:CHBK;WD:WD"}, searchoptions:{sopt: ['eq', 'ne'],value:":;FTD:FTD;RTD:RTD;CHBK:CHBK;WD:WD"}}
            ,{name:'amount', index:'amount', width:100, editable:true, search:true, sorttype:'integer', searchoptions:{sopt:['eq','ne','le','lt','gt','ge']}}
            ,{name:'Junior', index:'Junior',search:true, width:200, sorttype:'string', searchoptions:{sopt:['cn','eq','bw','bn','nc','ew','en']},editable:true,
                                                                    editoptions:{dataInit: function(e) {
                                                                    $(e).autocomplete({source:"autocomplete.php",
                                                                        minLength: 1});} }}
            ,{name:'Senior', index:'Senior',search:true, width:200, sorttype:'string', searchoptions:{sopt:['cn','eq','bw','bn','nc','ew','en']}, editable:true, editoptions:{dataInit: function(e) {
                                                                    $(e).autocomplete({source:"autocomplete.php",
                                                                        minLength: 1});} }}
            ,{name:'platform', index:'platform', width:100,  editable:true, search:true, stype:"select", searchoptions:{value:":[All];MRT:MRT;RTO:RTOption;XLR:XLR;FX:ForenX;PRFX:PRFX;PFX:PFX;10Brokers:10Brokers"}}
            ,{name:'name', index:'name', search:true, width:200,  editable:true, searchoptions:{sopt:['cn','eq','bw','bn','nc','ew','en']}}
            ,{name:'lead_name', index:'lead_name', editable:false, hidden:true}
            ,{name:'clients_id', index:'clients_id', editable:true, search:true, hidden:true}


        ],
      ondblClickRow: function(row_id){
            if (row_id) {
                jQuery('#grid').jqGrid('restoreRow', lastSel);
                jQuery('#grid').editRow(row_id, true);
                lastSel = row_id;
            }
        },
        pager: ('#pager'),
        rowNum:50,
        rowList:[50,100,200,1000],
        sortname: 'id',
        sortorder: "desc",
        viewrecords: true,
        editurl: './update.php',
       footerrow : true,
        userDataOnFooter : true

    })

    ;
    jQuery("#grid").jqGrid('navGrid',"#pager",{edit:false,add:false,del:true});
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
});
var timeoutHnd;
var flAuto = false;

function doSearch(ev){ if(!flAuto) return;
// var elem = ev.target||ev.srcElement;
if(timeoutHnd)
    clearTimeout(timeoutHnd)
    timeoutHnd = setTimeout(gridReload,500)
}
function gridReload(){
    var m_mask = jQuery("#month").val();
    var y_mask = jQuery("#year").val();
    var all = jQuery("#all").val();
    if (jQuery("#all").prop( "checked" ) ){
        jQuery("#grid").jqGrid('setGridParam',
            {url:"getdata.php?m_mask="+m_mask+"&y_mask="+y_mask+"&all="+all}).trigger("reloadGrid");
    }
    else
    jQuery("#grid").jqGrid('setGridParam',
        {url:"getdata.php?m_mask="+m_mask+"&y_mask="+y_mask}).trigger("reloadGrid");
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
