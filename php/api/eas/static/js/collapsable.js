
    var chart_config = {
        chart: {
            container: "#collapsable-example",

            animateOnInit: true,
            
            node: {
                collapsable: true
            },
            animation: {
                nodeAnimation: "easeOutBounce",
                nodeSpeed: 700,
                connectorsAnimation: "bounce",
                connectorsSpeed: 700
            }
        },
        nodeStructure: {
            image: "/static/images/img/server.svg",
            children: [
                {
                    image: "/static/images/img/wifi.svg",
                    collapsed: true,
                    children: [
                        {
                            image: "/static/images/img/wifi.svg"
                        }
                    ]
                },
                {
                    image: "/static/images/img/router.svg",
                    // childrenDropLevel: 1,
                    children: [
                        {
                            image: "/static/images/img/wifi.svg",
                            dinhz: "hello"
                        },
                        {
                            image: "/static/images/img/wifi.svg"
                        }

                    ]
                },
                {
                    pseudo: true,
                    // childrenDropLevel: 1,

                    children: [
                        {
                            image: "/static/images/img/wifi.svg"
                        },
                        {
                            image: "/static/images/img/wifi.svg"
                        }
                    ]
                }
     
            ]
        }
    };

/* Array approach
    var config = {
        container: "#collapsable-example",

        animateOnInit: true,
        
        node: {
            collapsable: true
        },
        animation: {
            nodeAnimation: "easeOutBounce",
            nodeSpeed: 700,
            connectorsAnimation: "bounce",
            connectorsSpeed: 700
        }
    },
    malory = {
        image: "img/malory.png"
    },

    lana = {
        parent: malory,
        image: "img/lana.png"
    }

    figgs = {
        parent: lana,
        image: "img/figgs.png"
    }

    sterling = {
        parent: malory,
        childrenDropLevel: 1,
        image: "img/sterling.png"
    },

    woodhouse = {
        parent: sterling,
        image: "img/woodhouse.png"
    },

    pseudo = {
        parent: malory,
        pseudo: true
    },

    cheryl = {
        parent: pseudo,
        image: "img/cheryl.png"
    },

    pam = {
        parent: pseudo,
        image: "img/pam.png"
    },

    chart_config = [config, malory, lana, figgs, sterling, woodhouse, pseudo, pam, cheryl];

*/