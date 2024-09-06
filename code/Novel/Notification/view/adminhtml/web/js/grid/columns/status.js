define([
    'underscore',
    'Magento_Ui/js/grid/columns/select'
    ], function (_, Column) {
    'use strict';
    
    return Column.extend({
    defaults: {
    bodyTmpl: 'Novel_Notification/grid/cells/status'
    },
    getOrderStatusColor: function (row) 
    {
        if (row.status == 1) 
            {
                return 'status-success';
            } else {
                return 'status-failed';
            }
        return '#303030';
    }
    });
    });