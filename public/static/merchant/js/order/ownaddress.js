define(["jquery", "easy-admin"], function ($, ea) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'order.ownaddress/index',
        add_url: 'order.ownaddress/add',
        delete_url: 'order.ownaddress/delete',
        modify_url: 'order.ownaddress/modify',

    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                skin: 'row',
                even: true,
                modifyReload: false,
                height: 'full-40',
                toolbar: ['refresh',
                    [{
                        text: ea.findText('添加'),
                        url: init.add_url,
                        method: 'open',
                        auth: 'add',
                        class: 'layui-btn layui-btn-normal layui-btn-sm',
                        icon: 'iconfont icon-jia1 ',
                    }],
                    'delete'
                    ],
                cols: [[
                    {type: 'checkbox'},
                    {field: 'id', width: 120,search:false,title: ea.findText('编号')},

                    {field: 'address', width: 380,  title: ea.findText('收款地址')},

                    {field: 'chain_type', title: ea.findText('链'),  selectList: {1: 'TRC20', 2: 'ERC20'},},
                    {field: 'img', minWidth: 180, title: ea.findText('地址二维码'), search: false, templet: ea.table.image},
                    {field: 'status', title: ea.findText('状态'), width: 95, selectList: {1: ea.findText('正常'),0:ea.findText('禁用')}, templet: ea.table.switch,tips:ea.findText('正常|禁用')},
                    {field: 'create_time',minWidth: 180, search:'range',title: ea.findText('创建时间')},
                    {field: 'update_time',minWidth: 180, search:false,title: ea.findText('更新时间')},
                    {

                        title: ea.findText('操作'),
                        templet: ea.table.tool,
                        operat: [

                            'delete']
                    }

                ]],
            });

            ea.listen();
        },
        add: function () {
            ea.listen();
        },
    };
    return Controller;
});
