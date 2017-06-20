var output = [];
var ws;
var server;
var chart;
var oldData;
var graphs = [];
var last = [];
var first = [];
var wCountries = {};

function dump(message) {
    console.log(message);
}

function start() {
    ws = new WebSocket(server);
    if (!ws) {
        dump("Error: WS Creation");
        return false;
    }

    ws.onmessage = function (evt) {
        var data = JSON.parse(evt.data);

        // Widgets
        output[0].innerText = formatNumber(data['lp']);
        output[1].innerText = formatNumber(data['mo']);
        output[2].innerText = formatNumber(data['mos']);

        var conv = 0;
        if (data['lp'] > 0) {
            conv = (parseFloat(formatNumber((data['mos'] / data['lp']) * 100))).toFixed(2);
        }

        output[3].innerText = conv + "%";

        // Map
        if (chart && oldData !== data['countries']) {
            oldData = data['countries'];
            var series = [];
            $.each(data['countries'], function (index, value) {
                if (index in iso.countries) {
                    series.push([
                        iso.countries[index]['ioc'],
                        value
                    ]);

                    if (!(index in wCountries)) {
                        $("#summary").append(
                            "<div class='col-lg-2'>" +
                            "<div class='ibox'>" +
                            "<div class='ibox-content' id='wc_" + index + "'>" +
                            "</div>" +
                            "</div>" +
                            "</div>"
                        );

                        wCountries[index] = $("#wc_" + index);
                    }

                    wCountries[index].html(
                        "<h1 class='no-margins'>" +
                        formatNumber(value) +
                        "</h1>" +
                        iso.countries[index]["name"]
                    );
                } else {
                    dump("Error: no index: " + index);
                }
            });

            var onlyValues = series.map(function (obj) {
                return obj[1];
            });

            var maxValue = Math.max.apply(null, onlyValues),
                paletteScale = d3.scale.linear().domain([0, maxValue]).range(["#EFEFFF", "#0d80ca"]);

            var dataset = {};
            series.forEach(function (item) {
                var iso = item[0],
                    value = item[1];
                dataset[iso] = {numberOfThings: value, fillColor: paletteScale(value)};
            });

            chart.updateChoropleth(dataset);
        }

        // Graphs
        updateGraphs(0, data['lp']);
        updateGraphs(1, data['mo']);
        updateGraphs(2, data['mos']);
        updateGraphs(3, parseInt(conv));
    };

    ws.onopen = function () {
        dump("Connected");
    };

    ws.onclose = function () {
        dump("Disconnected");
        ws = null;
        setTimeout(function () {
            start()
        }, 5000);
    };

    ws.onerror = function (evt) {
        dump("ERROR: " + evt.data);
    };
}

function updateGraphs(key, data) {
    var values = graphs[key].text().split(",");

    values.shift();
    if (!first[key] && data > last[key]) {
        values.push(data - last[key]);
    } else {
        values.push(0);
    }

    first[key] = false;
    last[key] = data;
    graphs[key].text(values.join(",")).change();
}

window.addEventListener("load", function () {
    output[0] = document.getElementById("output_lp");
    output[1] = document.getElementById("output_mo");
    output[2] = document.getElementById("output_mos");
    output[3] = document.getElementById("output_conv");

    graphs[0] = $(".output_lp_chart").peity("line", {fill: '#1ab394', stroke: '#169c81', width: 64});
    graphs[1] = $(".output_mo_chart").peity("line", {fill: '#1ab394', stroke: '#169c81', width: 64});
    graphs[2] = $(".output_mos_chart").peity("line", {fill: '#1ab394', stroke: '#169c81', width: 64});
    graphs[3] = $(".output_conv_chart").peity("line", {fill: '#1ab394', stroke: '#169c81', width: 64});

    last[0] = 0;
    last[1] = 0;
    last[2] = 0;
    last[3] = 0;

    first[0] = true;
    first[1] = true;
    first[2] = true;
    first[3] = true;

    start();

    chart = new Datamap({
        element: document.getElementById('world-map'),
        projection: 'mercator',
        responsive: true,
        fills: {defaultFill: '#F5F5F5'},
        geographyConfig: {
            borderColor: '#DEDEDE',
            highlightBorderWidth: 2,
            highlightFillColor: function (geo) {
                return geo['fillColor'] || '#F5F5F5';
            },
            highlightBorderColor: '#B7B7B7',
            popupTemplate: function (geo, data) {
                var text = '<div class="hoverinfo">' +
                    '<strong>' + geo.properties.name + '</strong>';

                if (data) {
                    text += '<br>LP Hits: <strong>' + formatNumber(data.numberOfThings) + '</strong>';
                }

                text += '</div>';
                return text;
            }
        },
        done: function (datamap) {
            datamap.svg.selectAll('.datamaps-subunit').on('click', function (geography) {
                showPopup(iso.findCountryByCode(geography.id)['alpha2']);
            });
        }
    });

    window.addEventListener('resize', function () {
        chart.resize();
    });
});

function formatNumber(number) {
    var nStr = number + '';
    var x = nStr.split('.');
    var x1 = x[0];
    var x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}

function reset() {
    output[0].innerText = 0;
    output[1].innerText = 0;
    output[2].innerText = 0;
    output[3].innerText = "0%";
    chart.updateChoropleth(
        null,
        {
            reset: true
        }
    );
}

function showPopup(code) {
    $.getJSON("/main/country?iso=" + code, function (data) {
        $('.modal_output_table_row').remove();
        if (data && data['total']['name'] !== null) {
            $('#modal_output_name').html(formatNumber(data['total']['name']));
            $('#modal_output_lp').html(formatNumber(data['total']['lp_hits']));
            $('#modal_output_mo').html(formatNumber(data['total']['mo']));
            $('#modal_output_mos').html(formatNumber(data['total']['mo_success']));

            var conv = 0;
            if (data['total']['lp_hits'] > 0) {
                conv = (parseFloat(formatNumber((data['total']['mo_success'] / data['total']['lp_hits']) * 100))).toFixed(2);
            }
            $('#modal_output_conv').html(conv + "%");

            var table = $('#modal_output_table');
            $.each(data, function (index, value) {
                if (index === 'total') {
                    return;
                }

                var convv = 0;
                if (value['cnt']['lp_hits'] > 0) {
                    convv = (parseFloat(formatNumber((value['cnt']['mo_success'] / value['cnt']['lp_hits']) * 100))).toFixed(2);
                }

                table.find('tbody').append(
                    '<tr class="modal_output_table_row">' +
                    '<td>' + value['op'] + '</td>' +
                    '<td class="text-right" style="width: 20%;">' + formatNumber(value['cnt']['lp_hits']) + '</td>' +
                    '<td class="text-right" style="width: 20%;">' + formatNumber(value['cnt']['mo']) + '</td>' +
                    '<td class="text-right" style="width: 20%;">' + formatNumber(value['cnt']['mo_success']) + '</td>' +
                    '<td class="text-right" style="width: 20%;">' + convv + '%</td>' +
                    '</tr>'
                );
            });

            $('#myModal').modal('show');
        }
    });

    return true;
}
