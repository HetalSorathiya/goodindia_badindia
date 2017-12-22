var ProjectName = {
    init: function () {
    },
    modules: [],
    initModules: function () {
        for (var module in ProjectName.modules) {
            var id = module.replace(/([A-Z])/g, '-$1').toLowerCase();
            id = id.substring(0, 1) == '-' ? id.substring(1) : id;
            if ($('#' + id).length && typeof (this.modules[module].init) == 'function') {
                ProjectName.modules[module].init();
            }
        }
    }
}
$(function () {
    ProjectName.init();
    ProjectName.initModules();
});
