gridOption.onComplete = function(grid) {
    //フィルター・ソート個人設定切り替え
    if (grid.readConfigApplyFlag != true) {
        this.readConfigApply(grid);
        grid.readConfigApplyFlag = true;
    }
}

//カラム個人設定切り替え
var columns;
try {
    if (columns = Sigma.Service.readConfigColumns(gridOption)) {
        gridOption.columns = columns;
    }
} catch (e) {
}
