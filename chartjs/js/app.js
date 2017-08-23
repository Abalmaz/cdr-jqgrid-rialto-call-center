function chart() {
    $.ajax({
        url: "data.php",
        dataType: "JSON",
        method: "GET",
        success: function (data) {
            console.log(data);
            var user = [];
            var calls = [];
            var min = [];

            for (var i in data) {
                user.push(data[i].userfield);
                calls.push(data[i].calls);
                min.push(data[i].minutes);
            }

            var chartdata = {
                labels: user,
                datasets: [
                    {
                        label: 'Calls',
                        //fillColor: '#7BC225',
                        backgroundColor: [
                            'rgba(132, 167, 208, 1)',
                          'rgba(132, 167, 208, 1)',
                          'rgba(132, 167, 208, 1)',
                          'rgba(132, 167, 208, 1)',
                          'rgba(132, 167, 208, 1)',
                          'rgba(132, 167, 208, 1)',
                          'rgba(132, 167, 208, 1)',
                          'rgba(132, 167, 208, 1)',
                          'rgba(132, 167, 208, 1)',
                          'rgba(132, 167, 208, 1)'
                            //'rgba(192, 80, 78, 1)',
                            //'rgba(185, 208, 138, 1)',
                            //'rgba(101, 210, 196, 1)',
                            //'rgba(166, 147, 189, 1)',
                            //'rgba(74, 172, 197, 1)',
                            //'rgba(247, 150, 71, 1)',
                            //'rgba(113, 136, 174, 1)',
                            //'rgba(132, 167, 208, 1)',
                            //'rgba(192, 80, 78, 1)'
                        ],
                        borderWidth: 1,
                        data: calls
                    },
                    {
                        label: 'Minutes',
                        backgroundColor: [
                            //'rgba(132, 167, 208, 0.5)',
                            'rgba(192, 80, 78, 1)',
                          'rgba(192, 80, 78, 1)',
                          'rgba(192, 80, 78, 1)',
                          'rgba(192, 80, 78, 1)',
                          'rgba(192, 80, 78, 1)',
                           'rgba(192, 80, 78, 1)',
                          'rgba(192, 80, 78, 1)',
                          'rgba(192, 80, 78, 1)',
                          'rgba(192, 80, 78, 1)',
                          'rgba(192, 80, 78, 1)'
                            //'rgba(185, 208, 138, 0.5)',
                            //'rgba(101, 210, 196, 0.5)',
                            //'rgba(166, 147, 189, 0.5)',
                            //'rgba(74, 172, 197, 0.5)',
                            //'rgba(247, 150, 71, 0.5)',
                            //'rgba(113, 136, 174, 0.5)',
                            //'rgba(132, 167, 208, 0.5)',
                            //'rgba(192, 80, 78, 0.5)'
                        ],
                        borderWidth: 1,
                        data: min
                    }
                ]
            };
            var ctx = document.getElementById("mycanvas").getContext('2d');

            var original = Chart.defaults.global.legend.onClick;
            Chart.defaults.global.legend.onClick = function (e, legendItem) {
                update_caption(legendItem);
                original.call(this, e, legendItem);
            };

            var barGraph = new Chart(ctx, {
                type: 'bar',
                data: chartdata
            });

            var labels = {
                "Max calls": true,
                "Minutes": true
            };

            var update_caption = function (legend) {
                labels[legend.text] = legend.hidden;

                var selected = Object.keys(labels).filter(function (key) {
                    return labels[key];
                });
            };
        }
    });}
$(document).ready(function(){
         chart();
         setInterval(chart, 180000);
});
