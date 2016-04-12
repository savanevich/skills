'use strict';

module.exports = ['itaRequestService', function (itaRequestService) {

        this.getTechnologies = function (getParams) {

                return itaRequestService.request(
                    {
                        url: '/api/v1/technologies',
                        method: 'GET',
                        params: getParams
                    });
        };

        this.getCategories = function () {

            return itaRequestService.request(
                {
                  url: '/api/v1/categories/',
                  method: 'GET'  
                });
        };
    }
];