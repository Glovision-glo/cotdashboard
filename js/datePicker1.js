$(function() {

                       pickerOpts = {
                            format: 'd-M-Y H:i:s',
                            timepicker:true,
                            datepicker:true,
                            changeMonth : true,
                            changeYear : true,
                            showSeconds: true,
                            showMonthAfterYear : true,
                        };
                         $("#fromdate").datetimepicker(pickerOpts);
                         $("#todate").datetimepicker(pickerOpts);

                         pickerOpts = {
                            format: 'H:i:s',
                            timepicker:true,
                            datepicker:false,
                            changeMonth : false,
                            changeYear : false,
                            showSeconds: true,
                            showMonthAfterYear : false,
                        };
                       //  $("#fromTime").datetimepicker(pickerOpts);
                     //    $("#toTime").datetimepicker(pickerOpts);

                });

function dateToEpochDB(indate)
{
    var tempdate=new Date();
    tempdate=parseInt(Math.round(indate.getTime()/1000.0));

    return tempdate;
}
function datestringToEpochDB(ds)
{
    if (ds) {
  
        var tempdate = stringFormatToDate(ds);
        return dateToEpochDB(tempdate);
    }
}

function dateToStringFormat(indate)
{
  
    var month = new Array();
    month[0] = "Jan";
    month[1] = "Feb";
    month[2] = "Mar";
    month[3] = "Apr";
    month[4] = "May";
    month[5] = "Jun";
    month[6] = "Jul";
    month[7] = "Aug";
    month[8] = "Sep";
    month[9] = "Oct";
    month[10] = "Nov";
    month[11] = "Dec";
  
    var d = indate.getDate();
    if (d < 10) {
        d = "0" + d;
    }
    var M = month[indate.getMonth()];
    var Y = indate.getFullYear();
 
    return (d + "-" + M + "-" + Y );
}
function EpochDBToDate(epoch){
 
    var date = new Date(parseInt(Math.round(epoch*1000)));
  
    return date;
}
function EpochDBToDateString(epoch){
  
    var date = EpochDBToDate(epoch);
    return dateToStringFormat(date);
}
function fuelDBConverts(from){
        var date=stringFormatToDate(from);

        var H = date.getHours();
    var i = date.getMinutes();
    var s = date.getSeconds();
        var d = date.getDate();
    if (d < 10) {
        d = "0" + d;
    }
    var M = date.getMonth();
    
    if (M < 9) {
        M = "0" + ++M;
    }
    else {
    
     M = ++M;
  

    }
    var Y = date.getFullYear();
   return Y+"-"+M+"-"+d+"  "+H+":"+i+":"+s ;

}
function stringFormatToDate(ds)
{
   
    var dsArray=ds.split(/[\s-:]+/);
    var year = dsArray[0];
    var day = dsArray[2];
    var month;
    if (dsArray[1]=="01") month = "Jan";
    else if (dsArray[1]=="02") month = "Feb";
    else if (dsArray[1]=="03") month = "Mar";
    else if (dsArray[1]=="04") month = "Apr";
    else if (dsArray[1]=="05") month = "May";
    else if (dsArray[1]=="06") month = "Jun";
    else if (dsArray[1]=="07") month = "Jul";
    else if (dsArray[1]=="08") month = "Aug";
    else if (dsArray[1]=="09") month ="Sep";
    else if (dsArray[1]=="10") month = "Oct";
    else if (dsArray[1]=="11") month = "Nov";
    else if (dsArray[1]=="12") month = "Dec";

    var hour = dsArray[3];
    var minute = dsArray[4];
    var second = dsArray[5];
    return day+"-"+month+"-"+year+"  "+ hour+":"+minute+":"+ second ;
}
function addOneDayToFromDate(){
        var date = new Date();
        date.setDate(date.getDate() - 1);
    document.getElementById("fromDate").value=dateToStringFormat(date);

}
