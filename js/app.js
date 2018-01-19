var wgs84Sphere = new ol.Sphere(6378137);

var raster = new ol.layer.Tile({
    source: new ol.source.OSM()
});

var source = new ol.source.Vector();

var vector = new ol.layer.Vector({
    source: source,
    style: new ol.style.Style({
        fill: new ol.style.Fill({
            color: 'rgba(255, 255, 255, 0.2)'
        }),
        stroke: new ol.style.Stroke({
            color: '#ffcc33',
            width: 2
        }),
        image: new ol.style.Circle({
            radius: 7,
            fill: new ol.style.Fill({
                color: '#ffcc33'
            })
        })
    })
});

var myDom = {
    points: {
        text: document.getElementById('points-text'),
        align: document.getElementById('points-align'),
        baseline: document.getElementById('points-baseline'),
        rotation: document.getElementById('points-rotation'),
        font: document.getElementById('points-font'),
        weight: document.getElementById('points-weight'),
        size: document.getElementById('points-size'),
        offsetX: document.getElementById('points-offset-x'),
        offsetY: document.getElementById('points-offset-y'),
        color: document.getElementById('points-color'),
        outline: document.getElementById('points-outline'),
        outlineWidth: document.getElementById('points-outline-width'),
        maxreso: document.getElementById('points-maxreso')
    },
    lines: {
        text: document.getElementById('lines-text'),
        align: document.getElementById('lines-align'),
        baseline: document.getElementById('lines-baseline'),
        rotation: document.getElementById('lines-rotation'),
        font: document.getElementById('lines-font'),
        weight: document.getElementById('lines-weight'),
        size: document.getElementById('lines-size'),
        offsetX: document.getElementById('lines-offset-x'),
        offsetY: document.getElementById('lines-offset-y'),
        color: document.getElementById('lines-color'),
        outline: document.getElementById('lines-outline'),
        outlineWidth: document.getElementById('lines-outline-width'),
        maxreso: document.getElementById('lines-maxreso')
    },
    polygons: {
        text: document.getElementById('polygons-text'),
        align: document.getElementById('polygons-align'),
        baseline: document.getElementById('polygons-baseline'),
        rotation: document.getElementById('polygons-rotation'),
        font: document.getElementById('polygons-font'),
        weight: document.getElementById('polygons-weight'),
        size: document.getElementById('polygons-size'),
        offsetX: document.getElementById('polygons-offset-x'),
        offsetY: document.getElementById('polygons-offset-y'),
        color: document.getElementById('polygons-color'),
        outline: document.getElementById('polygons-outline'),
        outlineWidth: document.getElementById('polygons-outline-width'),
        maxreso: document.getElementById('polygons-maxreso')
    }
};

var getText = function (feature, resolution, dom) {
    var type = dom.text.value;
    var maxResolution = dom.maxreso.value;
    var text = feature.get('name');

    if (resolution > maxResolution) {
        text = '';
    } else if (type == 'hide') {
        text = '';
    } else if (type == 'shorten') {
        text = text.trunc(12);
    } else if (type == 'wrap') {
        text = stringDivider(text, 16, '\n');
    }

    return text;
};


var createTextStyle = function (feature, resolution, dom) {
    var align = dom.align.value;
    var baseline = dom.baseline.value;
    var size = dom.size.value;
    var offsetX = parseInt(dom.offsetX.value, 10);
    var offsetY = parseInt(dom.offsetY.value, 10);
    var weight = dom.weight.value;
    var rotation = parseFloat(dom.rotation.value);
    var font = weight + ' ' + size + ' ' + dom.font.value;
    var fillColor = dom.color.value;
    var outlineColor = dom.outline.value;
    var outlineWidth = parseInt(dom.outlineWidth.value, 10);

    return new ol.style.Text({
        textAlign: align,
        textBaseline: baseline,
        font: font,
        text: getText(feature, resolution, dom),
        fill: new ol.style.Fill({color: fillColor}),
        stroke: new ol.style.Stroke({color: outlineColor, width: outlineWidth}),
        offsetX: offsetX,
        offsetY: offsetY,
        rotation: rotation
    });
};


// Polygons
function polygonStyleFunction(feature, resolution) {
    return new ol.style.Style({
        stroke: new ol.style.Stroke({
            color: 'blue',
            width: 1
        }),
        fill: new ol.style.Fill({
            color: 'rgba(0, 0, 255, 0.1)'
        }),
        text: createTextStyle(feature, resolution, myDom.polygons)
    });
}

var vectorPolygons = new ol.layer.Vector({
    source: new ol.source.Vector({
        url: 'data/geojson/polygon-samples.geojson',
        format: new ol.format.GeoJSON()
    }),
    style: polygonStyleFunction
});


// Lines
function lineStyleFunction(feature, resolution) {
    return new ol.style.Style({
        stroke: new ol.style.Stroke({
            color: 'green',
            width: 5
        }),
        text: createTextStyle(feature, resolution, myDom.lines)
    });
}

var vectorLines = new ol.layer.Vector({
    source: new ol.source.Vector({
        url: 'data/geojson/line-samples.geojson',
        format: new ol.format.GeoJSON()
    }),
    style: lineStyleFunction
});


// Points
function pointStyleFunction(feature, resolution) {
    return new ol.style.Style({
        image: new ol.style.Circle({
            radius: 10,
            fill: new ol.style.Fill({color: 'rgba(255, 0, 0, 0.1)'}),
            stroke: new ol.style.Stroke({color: 'red', width: 3})
        }),
        text: createTextStyle(feature, resolution, myDom.points)
    });
}

var vectorPoints = new ol.layer.Vector({
    source: new ol.source.Vector({
        url: 'data/geojson/point-samples.geojson',
        format: new ol.format.GeoJSON()
    }),
    style: pointStyleFunction
});

var mousePositionControl = new ol.control.MousePosition({
    coordinateFormat: ol.coordinate.createStringXY(10),
    projection: 'EPSG:4326',
    // comment the following two lines to have the mouse position
    // be placed within the map.
    className: 'custom-mouse-position',
    target: document.getElementById('mouse-position'),
    undefinedHTML: '&nbsp;'
});

var projectionSelect = document.getElementById('projection');
projectionSelect.addEventListener('change', function (event) {
    mousePositionControl.setProjection(event.target.value);
});

var precisionInput = document.getElementById('precision');
precisionInput.addEventListener('change', function (event) {
    var format = ol.coordinate.createStringXY(event.target.valueAsNumber);
    mousePositionControl.setCoordinateFormat(format);
});

var osm = new ol.layer.Tile({
    source: new ol.source.OSM()
});
var bing = new ol.layer.Tile({
    source: new ol.source.BingMaps({
        imagerySet: 'Road',
        key: ' gqVNfloISCI4p3jzmA3S~f9uI402l5ZCG-_d7iagAcg~AquLQ_2qUIHZSGa00DVwnREHcD6ZRYGcUdJBdvbaF0VMQZWh-3tLD-lvzVDfac0y'
    })
});


/**
 * Currently drawn feature.
 * @type {ol.Feature}
 */
var sketch;


/**
 * The help tooltip element.
 * @type {Element}
 */
var helpTooltipElement;


/**
 * Overlay to show the help messages.
 * @type {ol.Overlay}
 */
var helpTooltip;


/**
 * The measure tooltip element.
 * @type {Element}
 */
var measureTooltipElement;


/**
 * Overlay to show the measurement.
 * @type {ol.Overlay}
 */
var measureTooltip;


/**
 * Message to show when the user is drawing a polygon.
 * @type {string}
 */
var continuePolygonMsg = 'Nhấp chuột vào điểm tiếp theo để tiếp tục đo/Nhấp đúp chuột để dừng lại';


/**
 * Message to show when the user is drawing a line.
 * @type {string}
 */
var continueLineMsg = 'Nhấp chuột vào điểm tiếp theo để tiếp tục đo/Nhấp đúp chuột để dừng lại';


/**
 * Handle pointer move.
 * @param {ol.MapBrowserEvent} evt The event.
 */
var pointerMoveHandler = function (evt) {
    if (evt.dragging) {
        return;
    }
    /** @type {string} */
    var helpMsg = 'Nhấp chuột để bắt đầu vẽ đường do đạc';

    if (sketch) {
        var geom = (sketch.getGeometry());
        if (geom instanceof ol.geom.Polygon) {
            helpMsg = continuePolygonMsg;
        } else if (geom instanceof ol.geom.LineString) {
            helpMsg = continueLineMsg;
        }
    }

    helpTooltipElement.innerHTML = helpMsg;
    helpTooltip.setPosition(evt.coordinate);

    helpTooltipElement.classList.remove('hidden');
};

var map = new ol.Map({
    layers: [
        raster,
        //bing,
        vector,
        new ol.layer.Group({
            layers: [
                vectorPoints,
                vectorLines,
                vectorPolygons
            ]
        })
    ],
    target: 'map',
    view: new ol.View({
        center: ol.proj.fromLonLat([105.7812196468, 10.0326663005]),
        zoom: 17
    }),
    controls: ol.control.defaults().extend([
        new ol.control.ScaleLine(),
        new ol.control.FullScreen(),
        mousePositionControl,
        new ol.control.ZoomToExtent({
            label: 'C',
            tip: 'Trở về CUSC',
            extent: [
                105.7734841560, 10.0352887317
            ]
        }),
    ]),
});

function bindInputs(layerid, layer) {
    var visibilityInput = $(layerid + ' input.visible');
    visibilityInput.on('change', function () {
        layer.setVisible(this.checked);
    });
    visibilityInput.prop('checked', layer.getVisible());

    var opacityInput = $(layerid + ' input.opacity');
    opacityInput.on('input change', function () {
        layer.setOpacity(parseFloat(this.value));
    });
    opacityInput.val(String(layer.getOpacity()));
}
map.getLayers().forEach(function (layer, i) {
    bindInputs('#layer' + i, layer);
    if (layer instanceof ol.layer.Group) {
        layer.getLayers().forEach(function (sublayer, j) {
            bindInputs('#layer' + i + j, sublayer);
        });
    }
});

document.getElementById('refresh-points')
        .addEventListener('click', function () {
            vectorPoints.setStyle(pointStyleFunction);
        });

document.getElementById('refresh-lines')
        .addEventListener('click', function () {
            vectorLines.setStyle(lineStyleFunction);
        });

document.getElementById('refresh-polygons')
        .addEventListener('click', function () {
            vectorPolygons.setStyle(polygonStyleFunction);
        });

/**
 * @param {number} n The max number of characters to keep.
 * @return {string} Truncated string.
 */
String.prototype.trunc = String.prototype.trunc ||
        function (n) {
            return this.length > n ? this.substr(0, n - 1) + '...' : this.substr(0);
        };


// http://stackoverflow.com/questions/14484787/wrap-text-in-javascript
function stringDivider(str, width, spaceReplacer) {
    if (str.length > width) {
        var p = width;
        while (p > 0 && (str[p] != ' ' && str[p] != '-')) {
            p--;
        }
        if (p > 0) {
            var left;
            if (str.substring(p, p + 1) == '-') {
                left = str.substring(0, p + 1);
            } else {
                left = str.substring(0, p);
            }
            var right = str.substring(p + 1);
            return left + spaceReplacer + stringDivider(right, width, spaceReplacer);
        }
    }
    return str;
}

map.on('pointermove', pointerMoveHandler);

map.getViewport().addEventListener('mouseout', function () {
    helpTooltipElement.classList.add('hidden');
});

var typeSelect = document.getElementById('type');
var geodesicCheckbox = document.getElementById('geodesic');

var draw; // global so we can remove it later


/**
 * Format length output.
 * @param {ol.geom.LineString} line The line.
 * @return {string} The formatted length.
 */
var formatLength = function (line) {
    var length;
    if (geodesicCheckbox.checked) {
        var coordinates = line.getCoordinates();
        length = 0;
        var sourceProj = map.getView().getProjection();
        for (var i = 0, ii = coordinates.length - 1; i < ii; ++i) {
            var c1 = ol.proj.transform(coordinates[i], sourceProj, 'EPSG:4326');
            var c2 = ol.proj.transform(coordinates[i + 1], sourceProj, 'EPSG:4326');
            length += wgs84Sphere.haversineDistance(c1, c2);
        }
    } else {
        length = Math.round(line.getLength() * 100) / 100;
    }
    var output;
    if (length > 100) {
        output = (Math.round(length / 1000 * 100) / 100) +
                ' ' + 'km';
    } else {
        output = (Math.round(length * 100) / 100) +
                ' ' + 'm';
    }
    return output;
};


/**
 * Format area output.
 * @param {ol.geom.Polygon} polygon The polygon.
 * @return {string} Formatted area.
 */
var formatArea = function (polygon) {
    var area;
    if (geodesicCheckbox.checked) {
        var sourceProj = map.getView().getProjection();
        var geom = /** @type {ol.geom.Polygon} */(polygon.clone().transform(
                sourceProj, 'EPSG:4326'));
        var coordinates = geom.getLinearRing(0).getCoordinates();
        area = Math.abs(wgs84Sphere.geodesicArea(coordinates));
    } else {
        area = polygon.getArea();
    }
    var output;
    if (area > 10000) {
        output = (Math.round(area / 1000000 * 100) / 100) +
                ' ' + 'km<sup>2</sup>';
    } else {
        output = (Math.round(area * 100) / 100) +
                ' ' + 'm<sup>2</sup>';
    }
    return output;
};

function addInteraction() {
    var type = (typeSelect.value == 'area' ? 'Polygon' : 'LineString');
    draw = new ol.interaction.Draw({
        source: source,
        type: /** @type {ol.geom.GeometryType} */ (type),
        style: new ol.style.Style({
            fill: new ol.style.Fill({
                color: 'rgba(255, 255, 255, 0.2)'
            }),
            stroke: new ol.style.Stroke({
                color: 'rgba(0, 0, 0, 0.5)',
                lineDash: [10, 10],
                width: 2
            }),
            image: new ol.style.Circle({
                radius: 5,
                stroke: new ol.style.Stroke({
                    color: 'rgba(0, 0, 0, 0.7)'
                }),
                fill: new ol.style.Fill({
                    color: 'rgba(255, 255, 255, 0.2)'
                })
            })
        })
    });
    //map.addInteraction(draw);

    createMeasureTooltip();
    createHelpTooltip();

    var listener;
    draw.on('drawstart',
            function (evt) {
                // set sketch
                sketch = evt.feature;

                /** @type {ol.Coordinate|undefined} */
                var tooltipCoord = evt.coordinate;

                listener = sketch.getGeometry().on('change', function (evt) {
                    var geom = evt.target;
                    var output;
                    if (geom instanceof ol.geom.Polygon) {
                        output = formatArea(geom);
                        tooltipCoord = geom.getInteriorPoint().getCoordinates();
                    } else if (geom instanceof ol.geom.LineString) {
                        output = formatLength(geom);
                        tooltipCoord = geom.getLastCoordinate();
                    }
                    measureTooltipElement.innerHTML = output;
                    measureTooltip.setPosition(tooltipCoord);
                });
            }, this);

    draw.on('drawend',
            function () {
                measureTooltipElement.className = 'tooltip tooltip-static';
                measureTooltip.setOffset([0, -7]);
                // unset sketch
                sketch = null;
                // unset tooltip so that a new one can be created
                measureTooltipElement = null;
                createMeasureTooltip();
                ol.Observable.unByKey(listener);
            }, this);
}


/**
 * Creates a new help tooltip
 */
function createHelpTooltip() {
    if (helpTooltipElement) {
        helpTooltipElement.parentNode.removeChild(helpTooltipElement);
    }
    helpTooltipElement = document.createElement('div');
    helpTooltipElement.className = 'tooltip hidden';
    helpTooltip = new ol.Overlay({
        element: helpTooltipElement,
        offset: [15, 0],
        positioning: 'center-left'
    });
    //map.addOverlay(helpTooltip);
}


/**
 * Creates a new measure tooltip
 */
function createMeasureTooltip() {
    if (measureTooltipElement) {
        measureTooltipElement.parentNode.removeChild(measureTooltipElement);
    }
    measureTooltipElement = document.createElement('div');
    measureTooltipElement.className = 'tooltip tooltip-measure';
    measureTooltip = new ol.Overlay({
        element: measureTooltipElement,
        offset: [0, -15],
        positioning: 'bottom-center'
    });
    //map.addOverlay(measureTooltip);
}


/**
 * Let user change the geometry type.
 */
typeSelect.onchange = function () {
    map.removeInteraction(draw);
    addInteraction();
};

addInteraction();

$('#layertree li > span').click(function () {
    $(this).siblings('fieldset').toggle();
}).siblings('fieldset').hide();


// Create a popup overlay which will be used to display feature info
var popup = new ol.Overlay.Popup();
map.addOverlay(popup);

//// Add an event handler for the map "singleclick" event
map.on('singleclick', function (evt) {

    // Hide existing popup and reset it's offset
    popup.hide();
    popup.setOffset([0, 0]);

    // Attempt to find a feature in one of the visible vector layers
    var feature = map.forEachFeatureAtPixel(evt.pixel, function (feature, layer) {
        return feature;
    });

    if (feature) {

        var coord = feature.getGeometry().getCoordinates();
        var props = feature.getProperties();
//        var info = "<h2><a href='" + props.caseurl + "'>" + props.casereference + "</a></h2>";
//        info += "<p>" + props.locationtext + "</p>";
//        info += "<p>Status: " + props.status + " " + props.statusdesc + "</p>";
        
        var info = '<h2>'+ props.name +'</h2>';
        // Offset the popup so it points at the middle of the marker not the tip
        //popup.setOffset([0, -22]);
        popup.show(coord, info);

    }

});


/*
 var imageStyle = new ol.style.Style({
 image: new ol.style.Circle({
 radius: 5,
 snapToPixel: false,
 fill: new ol.style.Fill({color: 'yellow'}),
 stroke: new ol.style.Stroke({color: 'red', width: 1})
 })
 });
 
 var headInnerImageStyle = new ol.style.Style({
 image: new ol.style.Circle({
 radius: 2,
 snapToPixel: false,
 fill: new ol.style.Fill({color: 'blue'})
 })
 });
 
 var headOuterImageStyle = new ol.style.Style({
 image: new ol.style.Circle({
 radius: 5,
 snapToPixel: false,
 fill: new ol.style.Fill({color: 'black'})
 })
 });
 
 var n = 200;
 var omegaTheta = 30000; // Rotation period in ms
 var R = 7e6;
 var r = 2e6;
 var p = 2e6;
 map.on('postcompose', function (event) {
 var vectorContext = event.vectorContext;
 var frameState = event.frameState;
 var theta = 2 * Math.PI * frameState.time / omegaTheta;
 var coordinates = [];
 var i;
 for (i = 0; i < n; ++i) {
 var t = theta + 2 * Math.PI * i / n;
 var x = (R + r) * Math.cos(t) + p * Math.cos((R + r) * t / r);
 var y = (R + r) * Math.sin(t) + p * Math.sin((R + r) * t / r);
 coordinates.push([x, y]);
 }
 vectorContext.setStyle(imageStyle);
 vectorContext.drawGeometry(new ol.geom.MultiPoint(coordinates));
 
 var headPoint = new ol.geom.Point(coordinates[coordinates.length - 1]);
 
 vectorContext.setStyle(headOuterImageStyle);
 vectorContext.drawGeometry(headPoint);
 
 vectorContext.setStyle(headInnerImageStyle);
 vectorContext.drawGeometry(headPoint);
 
 map.render();
 });
 map.render();
 */