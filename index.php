<?php
//including the database connection file
include_once("./vendor/crud-php-simple/config.php");

//fetching data in descending order (lastest entry first)
//$result = mysql_query("SELECT * FROM users ORDER BY id DESC"); // mysql_query is deprecated
$result = mysqli_query($mysqli, "SELECT * FROM users ORDER BY id DESC"); // using mysqli_query instead
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="vendor/openlayers/css/ol.css" type="text/css">
        <link rel="stylesheet" href="css/main.css" type="text/css">
        <script src="vendor/jquery/jquery.js" type="text/javascript"></script>
        <script src="vendor/openlayers/build/ol.js" type="text/javascript"></script>
        <script src="vendor/ol3-popup/src/ol3-popup.js" type="text/javascript"></script>
        <title>CUSC Map</title>
    </head>
    <body>
        <h2>Demo CUSC Map</h2>
        <div id="map" class="map">
            <div id="popup"></div>
        </div>
        <div id="mouse-position"></div>
        <div id="layertree">
            <ul>
                <li><span>Lớp nền (Background Layer)</span>
                    <fieldset id="layer0">
                        <label class="checkbox" for="visible0">
                            <input id="visible0" class="visible" type="checkbox"/>Hiển thị
                        </label>
                        <label>Độ sáng/mờ</label>
                        <input class="opacity" type="range" min="0" max="1" step="0.01"/>
                    </fieldset>
                </li>
                <li><span>Nhóm các lớp vẽ (Layer group)</span>
                    <fieldset id="layer1">
                        <label class="checkbox" for="visible1">
                            <input id="visible1" class="visible" type="checkbox"/>Hiển thị
                        </label>
                        <label>Độ sáng/mờ</label>
                        <input class="opacity" type="range" min="0" max="1" step="0.01"/>
                    </fieldset>
                    <ul>
                        <li><span>Lớp vẽ các điểm (Point)</span>
                            <fieldset id="layer10">
                                <label class="checkbox" for="visible10">
                                    <input id="visible10" class="visible" type="checkbox"/>Hiển thị
                                </label>
                                <label>Độ sáng/mờ</label>
                                <input class="opacity" type="range" min="0" max="1" step="0.01"/>
                            </fieldset>
                        </li>
                        <li><span>Lớp vẽ các đường ống (Line)</span>
                            <fieldset id="layer11">
                                <label class="checkbox" for="visible11">
                                    <input id="visible11" class="visible" type="checkbox"/>Hiển thị
                                </label>
                                <label>Độ sáng/mờ</label>
                                <input class="opacity" type="range" min="0" max="1" step="0.01"/>
                            </fieldset>
                        </li>
                        <li><span>Lớp vẽ các vùng (Area)</span>
                            <fieldset id="layer12">
                                <label class="checkbox" for="visible12">
                                    <input id="visible12" class="visible" type="checkbox"/>Hiển thị
                                </label>
                                <label>Độ sáng/mờ</label>
                                <input class="opacity" type="range" min="0" max="1" step="0.01"/>
                            </fieldset>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>

        <form class="form-inline">
            <label>Kiểu tính chiều dài/diện tích &nbsp;</label>
            <select id="type">
                <option value="length">Chiều dài (Đo theo dạng đường)</option>
                <option value="area">Diện tích (Tính theo m2)</option>
            </select>
            <label class="checkbox">
                <input type="checkbox" id="geodesic">
                Dùng hệ tọa độ không gian để tính toán
            </label>
        </form>

        <div style="display:none;">
            <form>
                <label>Projection </label>
                <select id="projection">
                    <option value="EPSG:4326">EPSG:4326</option>
                    <option value="EPSG:3857">EPSG:3857</option>
                </select>
                <label>Precision </label>
                <input id="precision" type="number" min="0" max="12" value="4"/>
            </form>
        </div>

        <div style="display:none;">
            <div class="edit-form">
                <input id="refresh-points" type="button" value="Refresh" />
                <h2>Points</h2>
                <div class="edit-form-elem">
                    <label>Text: </label>
                    <select id="points-text">
                        <option value="hide">Hide</option>
                        <option value="normal">Normal</option>
                        <option value="shorten" selected="selected">Shorten</option>
                        <option value="wrap">Wrap</option>
                    </select>
                    <br />
                    <label title="Max Resolution Denominator">MaxReso.:</label>
                    <select id="points-maxreso">
                        <option value="38400">38,400</option>
                        <option value="19200">19,200</option>
                        <option value="9600">9,600</option>
                        <option value="4800">4,800</option>
                        <option value="2400">2,400</option>
                        <option value="1200" selected="selected">1,200</option>
                        <option value="600">600</option>
                        <option value="300">300</option>
                        <option value="150">150</option>
                        <option value="75">75</option>
                        <option value="32">32</option>
                        <option value="16">16</option>
                        <option value="8">8</option>
                    </select>
                    <br />
                    <label>Align: </label>
                    <select id="points-align">
                        <option value="center" selected="selected">Center</option>
                        <option value="end">End</option>
                        <option value="left">Left</option>
                        <option value="right">Right</option>
                        <option value="start">Start</option>
                    </select>
                    <br />
                    <label>Baseline: </label>
                    <select id="points-baseline">
                        <option value="alphabetic">Alphabetic</option>
                        <option value="bottom">Bottom</option>
                        <option value="hanging">Hanging</option>
                        <option value="ideographic">Ideographic</option>
                        <option value="middle" selected="selected">Middle</option>
                        <option value="top">Top</option>
                    </select>
                    <br />
                    <label>Rotation: </label>
                    <select id="points-rotation">
                        <option value="0">0°</option>
                        <option value="0.785398164">45°</option>
                        <option value="1.570796327">90°</option>
                    </select>
                    <br />
                    <label>Font: </label>
                    <select id="points-font">
                        <option value="Arial" selected="selected">Arial</option>
                        <option value="Courier New">Courier New</option>
                        <option value="Quattrocento Sans">Quattrocento</option>
                        <option value="Verdana">Verdana</option>
                    </select>
                    <br />
                    <label>Weight: </label>
                    <select id="points-weight">
                        <option value="bold">Bold</option>
                        <option value="normal" selected="selected">Normal</option>
                    </select>
                    <br />
                    <label>Size: </label>
                    <input type="text" value="12px" id="points-size" />
                    <br />
                    <label>Offset X:</label>
                    <input type="text" value="0" id="points-offset-x" />
                    <br />
                    <label>Offset Y:</label>
                    <input type="text" value="0" id="points-offset-y" />
                    <br />
                    <label>Color :</label>
                    <input type="text" value="#aa3300" id="points-color" />
                    <br />
                    <label title="Outline Color">O. Color:</label>
                    <input type="text" value="#ffffff" id="points-outline" />
                    <br />
                    <label title="Outline Width">O. Width :</label>
                    <input type="text" value="3" id="points-outline-width" />
                </div>
            </div>

            <div class="edit-form">
                <input id="refresh-lines" type="button" value="Refresh" />
                <h2>Lines</h2>
                <div class="edit-form-elem">
                    <label>Text: </label>
                    <select id="lines-text">
                        <option value="hide">Hide</option>
                        <option value="normal">Normal</option>
                        <option value="shorten">Shorten</option>
                        <option value="wrap" selected="selected">Wrap</option>
                    </select>
                    <br />
                    <label title="Max Resolution Denominator">MaxReso.:</label>
                    <select id="lines-maxreso">
                        <option value="38400">38,400</option>
                        <option value="19200">19,200</option>
                        <option value="9600">9,600</option>
                        <option value="4800">4,800</option>
                        <option value="2400">2,400</option>
                        <option value="1200" selected="selected">1,200</option>
                        <option value="600">600</option>
                        <option value="300">300</option>
                        <option value="150">150</option>
                        <option value="75">75</option>
                        <option value="32">32</option>
                        <option value="16">16</option>
                        <option value="8">8</option>
                    </select>
                    <br />
                    <label>Align: </label>
                    <select id="lines-align">
                        <option value="center" selected="selected">Center</option>
                        <option value="end">End</option>
                        <option value="left">Left</option>
                        <option value="right">Right</option>
                        <option value="start">Start</option>
                    </select>
                    <br />
                    <label>Baseline: </label>
                    <select id="lines-baseline">
                        <option value="alphabetic">Alphabetic</option>
                        <option value="bottom">Bottom</option>
                        <option value="hanging">Hanging</option>
                        <option value="ideographic">Ideographic</option>
                        <option value="middle" selected="selected">Middle</option>
                        <option value="top">Top</option>
                    </select>
                    <br />
                    <label>Rotation: </label>
                    <select id="lines-rotation">
                        <option value="0">0°</option>
                        <option value="0.785398164">45°</option>
                        <option value="1.570796327">90°</option>
                    </select>
                    <br />
                    <label>Font: </label>
                    <select id="lines-font">
                        <option value="Arial">Arial</option>
                        <option value="Courier New" selected="selected">Courier New</option>
                        <option value="Quattrocento Sans">Quattrocento</option>
                        <option value="Verdana">Verdana</option>
                    </select>
                    <br />
                    <label>Weight: </label>
                    <select id="lines-weight">
                        <option value="bold" selected="selected">Bold</option>
                        <option value="normal">Normal</option>
                    </select>
                    <br />
                    <label>Size: </label>
                    <input type="text" value="16px" id="lines-size" />
                    <br />
                    <label>Offset X:</label>
                    <input type="text" value="0" id="lines-offset-x" />
                    <br />
                    <label>Offset Y:</label>
                    <input type="text" value="0" id="lines-offset-y" />
                    <br />
                    <label>Color :</label>
                    <input type="text" value="green" id="lines-color" />
                    <br />
                    <label title="Outline Color">O. Color:</label>
                    <input type="text" value="#ffffff" id="lines-outline" />
                    <br />
                    <label title="Outline Width">O. Width :</label>
                    <input type="text" value="3" id="lines-outline-width" />
                </div>
            </div>

            <div class="edit-form">
                <input id="refresh-polygons" type="button" value="Refresh" />
                <h2>Polygons</h2>
                <div class="edit-form-elem">
                    <label>Text: </label>
                    <select id="polygons-text">
                        <option value="hide">Hide</option>
                        <option value="normal" selected="selected">Normal</option>
                        <option value="shorten">Shorten</option>
                        <option value="wrap">Wrap</option>
                    </select>
                    <br />
                    <label title="Max Resolution Denominator">MaxReso.:</label>
                    <select id="polygons-maxreso">
                        <option value="38400">38,400</option>
                        <option value="19200">19,200</option>
                        <option value="9600">9,600</option>
                        <option value="4800">4,800</option>
                        <option value="2400">2,400</option>
                        <option value="1200" selected="selected">1,200</option>
                        <option value="600">600</option>
                        <option value="300">300</option>
                        <option value="150">150</option>
                        <option value="75">75</option>
                        <option value="32">32</option>
                        <option value="16">16</option>
                        <option value="8">8</option>
                    </select>
                    <br />
                    <label>Align: </label>
                    <select id="polygons-align">
                        <option value="center" selected="selected">Center</option>
                        <option value="end">End</option>
                        <option value="left">Left</option>
                        <option value="right">Right</option>
                        <option value="start">Start</option>
                    </select>
                    <br />
                    <label>Baseline: </label>
                    <select id="polygons-baseline">
                        <option value="alphabetic">Alphabetic</option>
                        <option value="bottom">Bottom</option>
                        <option value="hanging">Hanging</option>
                        <option value="ideographic">Ideographic</option>
                        <option value="middle" selected="selected">Middle</option>
                        <option value="top">Top</option>
                    </select>
                    <br />
                    <label>Rotation: </label>
                    <select id="polygons-rotation">
                        <option value="0">0°</option>
                        <option value="0.785398164">45°</option>
                        <option value="1.570796327">90°</option>
                    </select>
                    <br />
                    <label>Font: </label>
                    <select id="polygons-font">
                        <option value="Arial">Arial</option>
                        <option value="Courier New">Courier New</option>
                        <option value="Quattrocento Sans">Quattrocento</option>
                        <option value="Verdana" selected="selected">Verdana</option>
                    </select>
                    <br />
                    <label>Weight: </label>
                    <select id="polygons-weight">
                        <option value="bold" selected="selected">Bold</option>
                        <option value="normal">Normal</option>
                    </select>
                    <br />
                    <label>Size: </label>
                    <input type="text" value="10px" id="polygons-size" />
                    <br />
                    <label>Offset X:</label>
                    <input type="text" value="0" id="polygons-offset-x" />
                    <br />
                    <label>Offset Y:</label>
                    <input type="text" value="0" id="polygons-offset-y" />
                    <br />
                    <label>Color :</label>
                    <input type="text" value="blue" id="polygons-color" />
                    <br />
                    <label title="Outline Color">O. Color:</label>
                    <input type="text" value="#ffffff" id="polygons-outline" />
                    <br />
                    <label title="Outline Width">O. Width :</label>
                    <input type="text" value="3" id="polygons-outline-width" />
                </div>
            </div>
        </div>
        <div class="clearall"></div>
        <script src="js/app.js"></script>
    </body>
</html>