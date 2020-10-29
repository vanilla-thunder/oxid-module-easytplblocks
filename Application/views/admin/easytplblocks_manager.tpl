<!doctype html>
<html lang="en" ng-app="app">
<head>
    <title>Template Block Manager</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.8.0/angular-csp.min.css"
          integrity="sha512-nptw3cPhphu13Dy21CXMS1ceuSy2yxpKswAfZ7bAAE2Lvh8rHXhQFOjU+sSnw4B+mEoQmKFLKOj8lmXKVk3gow==" crossorigin="anonymous"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lumx/1.9.11/lumx.min.css"
          integrity="sha512-L5XFJ2nYd6N5NQR+a9Ex97WfBNXKTPZ8q2vBVZ5HocqYD+lFERHb92DosnLg8kfi2+A7xmofNA4LwIIjE7/zww==" crossorigin="anonymous"/>
</head>
<body ng-controller="ctrl" class="p-4">
<div class="container p+">


    <table class="mb++" border="0" cellspacing="0" cellpadding="5" style="border-collapse: collapse;">
        <colgroup>
            <col width="75"/>
            <col width="*"/>
            <col width="*"/>
            <col width="100"/>
        </colgroup>
        <thead>
        <tr style="border: 0; border-bottom: 1px solid #cfcfcf;">
            <th>Aktiv</th>
            <th align="left">Template</th>
            <th align="left">Block Name</th>
            <th align="right">Aktionen</th>
        </tr>
        </thead>
        <tbody>
        <tr ng-if="tplblocks.length == 0">
            <td colspan="4" class="bgc-yellow-300">es gibt noch keine Template Blöcke</td>
        </tr>
        <tr ng-repeat="block in tplblocks" style="border: 0; border-bottom: 1px solid #cfcfcf;">
            <td class="pv-" align="center">
                <lx-button lx-type="icon" lx-size="s" lx-color="{{ (block.OXACTIVE == 1 ? 'green' : 'red') }}" lx-tooltip="umschalten"
                           ng-click="toggleBlock(block)"><i class="mdi mdi-power"></i></lx-button>
            </td>
            <td class="pv-" ng-click="editBlock(block)" ng-bind="block.OXTEMPLATE"></td>
            <td class="pv-" ng-click="editBlock(block)" ng-bind="block.OXBLOCKNAME"></td>
            <td class="pv-" align="right">
                <lx-button lx-type="icon" lx-size="s" ng-click="editBlock(block)" lx-tooltip="bearbeiten"><i class="mdi mdi-pencil"></i></lx-button>
                <lx-button lx-type="icon" lx-size="s" lx-color="red" ng-click="deleteBlock(block)" lx-tooltip="löschen"><i class="mdi mdi-delete"></i></lx-button>

            </td>
        </tr>
        </tbody>
    </table>


    <lx-button lx-type="raised" ng-click="LxDialogService.open('newblock')">Block hinzufügen</lx-button>

    <lx-dialog id="newblock">
        <lx-dialog-header>
            <div class="toolbar bgc-primary pl++">
            <span class="toolbar__label tc-white fs-title">
                Neuen Block hinzufügen
            </span>
            </div>
        </lx-dialog-header>
        <lx-dialog-content>
            <form action="#" class="p+" autocomplete="off">
                <input autocomplete="false" name="hidden" type="text" style="display:none;">
                <div flex-container="row">
                    <div flex-item>
                        <lx-text-field lx-label="Template" lx-allow-clear="false">
                            <input type="text" ng-model="blockData.OXTEMPLATE" list="oxtemplateautocomplete" ng-blur="getOxblocknameAutocomplete()">
                            <datalist id="oxtemplateautocomplete">
                                <option ng-repeat="tpl in oxtemplateautocomplete" ng-value="tpl">
                            </datalist>
                        </lx-text-field>
                    </div>
                    <div flex-item>
                        <lx-text-field lx-label="Block Name" lx-allow-clear="false">
                            <input type="text" ng-model="blockData.OXBLOCKNAME" list="oxblocknameautocomplete" >
                            <datalist id="oxblocknameautocomplete">
                                <option ng-repeat="block in oxblocknameautocomplete" ng-value="block">
                            </datalist>
                        </lx-text-field>
                    </div>
                </div>

                <div ng-if="blockData.OXTEMPLATE == oxblocknameautocompletefile && oxblocknameautocomplete.length == 0"
                     class="col-12 bgc-yellow-400">Datei {{blockData.OXTEMPLATE}} hat keine TPL Blöcke
                </div>

                <lx-text-field lx-label="Inhalt">
                    <textarea ng-model="blockData.OXCONTENT"></textarea>
                </lx-text-field>
                <lx-button ng-if="blockData.OXCONTENT && blockData.OXCONTENT.indexOf('$smarty.block.paren') < 1" lx-color="black"
                           ng-click="blockData.OXCONTENT = blockData.OXCONTENT + '\n[{literal}][{$smarty.block.parent}][{/literal}]';" lx-type="flat">
                    [{literal}][{$smarty.block.parent}][{/literal}] einfügen
                </lx-button>

                <div class="p+" style="width: 150px; margin-left: auto">
                    <lx-switch lx-color="green" class="mt+" ng-model="blockData.OXACTIVE" ng-true-value="'1'" ng-false-value="'0'">Aktiv</lx-switch>
                </div>
            </form>
        </lx-dialog-content>
        <lx-dialog-footer>
            <lx-progress lx-type="circular" lx-diameter="75" ng-if="loading > 0"></lx-progress>
            <lx-button lx-color="black" lx-type="flat" lx-dialog-close>Abbrechen</lx-button>
            <lx-button lx-color="green" ng-click="saveBlock()">Speichern</lx-button>
        </lx-dialog-footer>
    </lx-dialog>
</div>

<script integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg=="
        src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" crossorigin="anonymous"></script>
<script integrity="sha512-+VS2+Nl1Qit71a/lbncmVsWOZ0BmPDkopw5sXAS2W+OfeceCEd9OGTQWjgVgP5QaMV4ddqOIW9XLW7UVFzkMAw=="
        src="https://cdnjs.cloudflare.com/ajax/libs/velocity/2.0.6/velocity.min.js" crossorigin="anonymous"></script>
<script integrity="sha512-jiG+LwJB0bmXdn4byKzWH6whPpnLy1pnGA/p3VCXFLk4IJ/Ftfcb22katPTapt35Q6kwrlnRheK6UPOIdJhYTA=="
        src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.8.0/angular.min.js" crossorigin="anonymous"></script>
<script integrity="sha512-1oGTN2yNg/Zya51MStoDkIDe3YFHFQaVTgULAVxpEodxVpGj4AG6z1l0734SCTPliWh15dlJHORpRKhzCAS+MQ=="
        src="https://cdnjs.cloudflare.com/ajax/libs/lumx/1.9.11/lumx.min.js" crossorigin="anonymous"></script>
<script>
    var app = angular.module('app', ['lumx']);
    app.controller('ctrl', function ($scope, $http, LxDialogService)
    {
        $scope.LxDialogService = LxDialogService;
        $scope.loading = 0;
        var oxGet = function ($fnc, $data)
        {
            return $http({
                method: 'POST',
                url: '[{ $oViewConf->getSelfLink()|replace:"&amp;":"&" }]',
                headers: {
                    "Content-Type": "application/json"
                },
                params: {
                    cl: '[{$oView->getClassName()}]',
                    fnc: $fnc
                },
                data: $data || {}
            });
        };

        $scope.tplblocks = [];
        $scope.getTplBLocks = function ()
        {
            $scope.loading++;
            oxGet('getTplBLocks', {}).then(
                function success(response)
                {
                    $scope.loading--;
                    $scope.tplblocks = response.data;
                },
                function error(response)
                {
                    $scope.loading--;
                    console.log("error", response);
                });
        };
        $scope.getTplBLocks();

        $scope.oxtemplateautocomplete = [{$oView->getOxtemplateAutocomplete()}];
        $scope.oxblocknameautocompletefile = '';
        $scope.oxblocknameautocomplete = [];
        $scope.getOxblocknameAutocomplete = function (newValue, oldValue)
        {
            if (!$scope.blockData.OXTEMPLATE || $scope.blockData.OXTEMPLATE === $scope.oxblocknameautocompletefile) return false;

            $scope.loading++;
            oxGet('getOxblocknameAutocomplete', {
                oxtemplate: $scope.blockData.OXTEMPLATE
            }).then(
                function success(response)
                {
                    $scope.loading--;
                    $scope.oxblocknameautocompletefile = $scope.blockData.OXTEMPLATE;
                    $scope.oxblocknameautocomplete = response.data;
                    console.log("success", response);
                },
                function error(response)
                {
                    $scope.loading--;
                    console.log("error", response);
                });

        };

        $scope.blockData = {};

        $scope.toggleBlock = function (block)
        {
            if (!block.OXID)
            {
                LxNotificationService.error('Fehler: der Block hat keine oxID. Bitte versuche es nochmal oder melde das Problem dem Entwickler.');
                return false;
            }
            $scope.loading++;
            oxGet('toggleBlock', {
                oxid: block.OXID
            }).then(
                function success(response)
                {
                    $scope.loading--;
                    $scope.getTplBLocks();
                },
                function error(response)
                {
                    $scope.loading--;
                    console.log("error", response);
                });

        };
        $scope.editBlock = function (block)
        {
            $scope.blockData = block;
            LxDialogService.open('newblock');
        };
        $scope.saveBlock = function ()
        {
            $scope.loading++;
            oxGet('saveBlock', {
                block: $scope.blockData
            }).then(
                function success(response)
                {
                    console.log("success", response);

                    $scope.loading--;
                    $scope.getTplBLocks();
                    LxDialogService.close('newblock');
                },
                function error(response)
                {
                    console.log("error", response);
                    $scope.loading--;
                });

        };
        $scope.deleteBlock = function (block)
        {
            if (!block.OXID)
            {
                LxNotificationService.error('Fehler: der Block hat keine oxID. Bitte versuche es nochmal oder melde das Problem dem Entwickler.');
                return false;
            }
            if (!confirm("wirklich löschen?")) return false;

            $scope.loading++;
            oxGet('deleteBlock', {
                oxid: block.OXID
            }).then(
                function success(response)
                {
                    $scope.loading--;
                    $scope.getTplBLocks();
                },
                function error(response)
                {
                    $scope.loading--;
                    console.log("error", response);
                });

        };
    });
</script>
</body>
</html>