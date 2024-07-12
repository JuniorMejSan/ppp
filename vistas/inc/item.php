<script src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
  google.charts.load('current', {packages: ['corechart']});
  google.charts.setOnLoadCallback(drawColumnChart);
  google.charts.setOnLoadCallback(drawBarChart);
  
  google.charts.setOnLoadCallback(drawColumnChart_Totales);

  function drawColumnChart() {
            $.ajax({
                url: '../ajax/itemAjax.php',
                method: 'POST',
                data: { accion: 'obtener_datos_grafico' },
                success: function(response) {
                    const data = JSON.parse(response);
                    const dataArray = [["Producto", "Total Vendido S/ ", { role: "style" }]];
                    data.forEach(item => {
                        dataArray.push([item.item_nombre, parseFloat(item.monto_total_generado), "color: #FF9933"]);
                      });

                    const dataTable = google.visualization.arrayToDataTable(dataArray);
                    const view = new google.visualization.DataView(dataTable);
                    view.setColumns([0, 1,
                                     { calc: "stringify",
                                       sourceColumn: 1,
                                       type: "string",
                                       role: "annotation" },
                                     2]);

                    const options = {
                        title: "Top Productos con Mayores Ingresos",
                        titleTextStyle: {
                            fontSize: 16,
                            alignment: 'center'
                        },
                        width: 600,
                        height: 400,
                        bar: {groupWidth: "95%"},
                        legend: { position: "none" },
                        hAxis: {
                            title: 'Productos',
                            titleTextStyle: {
                                fontSize: 16,
                                italic: false
                            }
                        },
                        vAxis: {
                            title: 'Total Vendido - S/.',
                            titleTextStyle: {
                                fontSize: 16,
                                italic: false
                            }
                        }
                    };

                    const chart = new google.visualization.ColumnChart(document.getElementById("grafico_masIngresos"));
                    chart.draw(view, options);
                },
                error: function() {
                    alert('Error al cargar los datos del gráfico de columnas');
                }
            });
        }


        function drawColumnChart_Totales() {
            $.ajax({
                url: '../ajax/itemAjax.php',
                method: 'POST',
                data: { accion: 'obtener_vendidos_grafico' },
                success: function(response) {
                    const data = JSON.parse(response);
                    const dataArray = [["Producto", "Total Vendido", { role: "style" }]];
                    data.forEach(item => {
                        dataArray.push([item.item_nombre, parseFloat(item.total_vendido), "color: #FF9933"]);
                      });

                    const dataTable = google.visualization.arrayToDataTable(dataArray);
                    const view = new google.visualization.DataView(dataTable);
                    view.setColumns([0, 1,
                                     { calc: "stringify",
                                       sourceColumn: 1,
                                       type: "string",
                                       role: "annotation" },
                                     2]);

                    const options = {
                        title: "Top 5 Productos mas Vendidos",
                        titleTextStyle: {
                            fontSize: 16,
                            alignment: 'center'
                        },
                        width: 600,
                        height: 400,
                        bar: {groupWidth: "95%"},
                        legend: { position: "none" },
                        hAxis: {
                            title: 'Productos',
                            titleTextStyle: {
                                fontSize: 16,
                                italic: false
                            }
                        },
                        vAxis: {
                            title: 'Total Vendido',
                            titleTextStyle: {
                                fontSize: 16,
                                italic: false
                            }
                        }
                    };

                    const chart = new google.visualization.ColumnChart(document.getElementById("grafico_Top5"));
                    chart.draw(view, options);
                },
                error: function() {
                    alert('Error al cargar los datos del gráfico de columnas');
                }
            });
        }



        function drawBarChart() {
            $.ajax({
                url: '../ajax/itemAjax.php',
                method: 'POST',
                data: { accion: 'obtener_datos_stock' },
                success: function(response) {
                    const data = JSON.parse(response);
                    const dataArray = [["Producto", "Stock", { role: "style" }]];
                    data.forEach(item => {
                        dataArray.push([item.item_nombre, parseInt(item.item_stock), "color: #FF9933"]);
                    });

                    const dataTable = google.visualization.arrayToDataTable(dataArray);
                    const view = new google.visualization.DataView(dataTable);
                    view.setColumns([0, 1,
                                     { calc: "stringify",
                                       sourceColumn: 1,
                                       type: "string",
                                       role: "annotation" },
                                     2]);

                    const options = {
                        title: "Stock de Productos",
                        titleTextStyle: {
                            fontSize: 16,
                            alignment: 'center'
                        },
                        width: 800,
                        height: 500,
                        bar: {groupWidth: "95%"},
                        legend: { position: "none" },
                        hAxis: {
                            title: 'Stock',
                            titleTextStyle: {
                                fontSize: 16,
                                italic: false
                            }
                        },
                        vAxis: {
                            title: 'Productos',
                            titleTextStyle: {
                                fontSize: 16,
                                italic: false
                            }
                        }
                    };

                    const chart = new google.visualization.BarChart(document.getElementById("grafico_stock"));
                    chart.draw(view, options);
                },
                error: function() {
                    alert('Error al cargar los datos del gráfico de barras');
                }
            });
        }
</script>
