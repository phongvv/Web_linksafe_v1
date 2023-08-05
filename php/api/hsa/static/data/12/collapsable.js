var chart_config = {
    chart: {
        container: "#collapsable-example",
        animateOnInit: true,
        rootOrientation: 'NORTH',
        scrollbar: 'fancy',
        node: {
            collapsable: true
        },

        connectors: {
            type: 'bCurve',
            style: {
                stroke: '#17a2b8'
            }
        },

        animation: {
            nodeAnimation: "easeInOutBounce",
            nodeSpeed: 600,
            connectorsAnimation: "backOut",
            connectorsSpeed: 700
        }
    },
    nodeStructure: {
        image: "/api/hsa/static/images/img/server.svg",
        text: {
            title: "HQA"
        },
        "children": [
{"id": "4","image": "/api/hsa/static/images/img/router.svg",text: {name: 'HAIDUONG'}},
{"id": "2","image": "/api/hsa/static/images/img/router.svg",text: {name: 'Lancs Bac Tu Liem'},link: {href: "../bsa/dashboard?branch_id=2"},"children":
 [{"id": "9","image": "/api/hsa/static/images/img/user.svg",text: {name: 'TaNgocDung'},"children":
 [{"dev_id": "35","image": "/api/hsa/static/images/img/wifi.svg",text: {name: 'LANCS_5c50'},link: {href: "../ea/dashboard?edge_id=35"}},{"dev_id": "36","image": "/api/hsa/static/images/img/wifi.svg",text: {name: 'LANCS_5cc4'},link: {href: "../ea/dashboard?edge_id=36"}},
],},
],},{"id": "1","image": "/api/hsa/static/images/img/router.svg",text: {name: 'Lancs Hanoi'},link: {href: "../bsa/dashboard?branch_id=1"},"children":
 [{"id": "4","image": "/api/hsa/static/images/img/user.svg",text: {name: 'WirelessZone'}},
{"id": "5","image": "/api/hsa/static/images/img/user.svg",text: {name: 'OfficeZone'}},
{"id": "7","image": "/api/hsa/static/images/img/user.svg",text: {name: 'TestingZone'}},

],},{"id": "3","image": "/api/hsa/static/images/img/router.svg",text: {name: 'Lancs Dong ANh'},link: {href: "../bsa/dashboard?branch_id=3"},"children":
 [{"id": "18","image": "/api/hsa/static/images/img/user.svg",text: {name: 'NguyenVanVu'},"children":
 [{"dev_id": "37","image": "/api/hsa/static/images/img/wifi.svg",text: {name: 'LANCS_5ca8'},link: {href: "../ea/dashboard?edge_id=37"}},{"dev_id": "38","image": "/api/hsa/static/images/img/router5.svg",text: {name: 'LANCS'},link: {href: "../ea/dashboard?edge_id=38"}},
],},
],},
]}};