var gridOption = {
    id                  : 'taskLog'
    , width             : "98%"
    , height            : "95%"
    , container         : 'taskGrid'
    , replaceContainer  : true
    , dataset           : dsOption
    , columns           : colsOption
    , toolbarPosition   : 'top'
    , toolbarContent    : ' help | skin | print | csv | reload | nav | goto | pagesize | filter filterbooks | state'
    , showGridMenu      : true
    , allowCustomSkin   : true
    , allowGroup        : true
    , allowFreeze       : true
    , allowHide         : true
    , remotePaging      : false
    , pageSize          : 10000
    , pageSizeList      : [10,20,50,100,1000,10000]
    , resizable         : false
};

Sigma.GridDefault.autoSelectFirstRow = false;
