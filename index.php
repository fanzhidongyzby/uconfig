<!DOCTYPE html>
<html ng-app="" lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <title>属性更新</title>
    <!-- 新 Bootstrap 核心 CSS 文件 -->
    <!-- <link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.4/css/bootstrap.min.css"> -->
    <link rel="stylesheet" href="bootstrap.min.css">
</head>

<body ng-controller="refresh">
    <form role="form" class="form-horizontal">
        <fieldset>
            <div class="text-center">
                <h1>属性更新<h1><hr></div>
            <div class="form-group">
                <!-- Text input-->
                <label class="col-sm-4 control-label" for="input01">检索属性</label>
                <div class="col-sm-5">
                    <input type="text" placeholder="输入关键字" class="form-control" ng-model="keyword" 
                    ng-change="changekeyword()">
                </div>
            </div>
            <div class="form-group">
                <!-- Select Multiple -->
                <label class="col-sm-4 control-label">检索结果</label>
                <div class="col-sm-5">
                    <select ng-model="selected" class="form-control" id="result" size="10" multiple="multiple" ng-options="v.key+' => '+v.value for v in values" ng-change="getvalue()">
                    </select>
                </div>
            </div>
            <div class="form-group">
                <!-- Textarea -->
                <label class="col-sm-4 control-label">替换内容</label>
                <div class="col-sm-5">
                    <div class="textarea">
                        <textarea rows="10" ng-model="newvalue" ng-change="changevalue()" class="form-control"></textarea>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">更新属性</label>
                <!-- Button -->
                <div class="col-sm-5">
                    <button class="btn btn-danger" ng-disabled="disSubmit"  ng-click="submit()">提交</button>
                </div>
            </div>
        </fieldset>
    </form>
</body>
    <!-- // <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.5/angular.min.js"></script> -->
	<script src="angular.min.js"></script>
    <script type="text/javascript">
        function refresh ($scope, $http) {
            $scope.values = [];
			$scope.valuechanged = false;
			$scope.disSubmit = false;
			$scope.changevalue = function() {
				$scope.valuechanged = true;
			}
            $scope.changekeyword = function() {
				$scope.newvalue = "";
				$scope.selected = [];
				$http({
					method: "POST",
					url: "search.php",
					data: "keyword=" + $scope.keyword,
					headers: {"Content-Type": "application/x-www-form-urlencoded"}
				}).success(function(data, status){
					if (status != 200) {
						return;
					}
					len = data.length;
					if (len > 20) len = 20;
					if (len < 10) len = 10;
					document.getElementById("result").size = len;
					$scope.values = data;
				}).error(function(data, status) {
					
				});
            };
            $scope.getvalue = function() {
                kvs = $scope.selected;
                value = "";
                if (kvs) {
                    value = kvs[0].value;
                }
				if ($scope.valuechanged) {
                    if(!confirm("你重新选择了key，要保留对value的更改吗？")) {
                        return;
                    }
				}
				$scope.newvalue = value;
				$scope.valuechanged = false;
            };
            $scope.submit = function() {
                kvs = $scope.selected;
                if(kvs) {
                    key = kvs[0].key;
                    old = kvs[0].value;
                    value = $scope.newvalue.replace(new RegExp('\n', 'g'), '');
                    //修改属性和值
                    if(!confirm("是否确认提交信息？\nkey = " + key + "\nvalue = "+ value + "\n")) {
						return;
					}
					$http({
						method: "POST",
						url: "replace.php",
						data: "key=" + key + "&old=" + old + "&new=" + value,
						headers: {"Content-Type": "application/x-www-form-urlencoded"}
					}).success(function(data, status){
						$scope.changekeyword();
						$scope.valuechanged = false;
						$scope.disSubmit = false;
					}).error(function(data, status) {
						
					});
					$scope.disSubmit = true;
                }
            };
        }
    </script>
</html>
