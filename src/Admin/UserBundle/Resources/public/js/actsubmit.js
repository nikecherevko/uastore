EnableSubmit = function(val)
{
    var sbmt = document.getElementById("_submit");

    if (val.checked == true)
    {
        sbmt.disabled = false;
    }
    else
    {
        sbmt.disabled = true;
    }
}     