<?php

array_map(function ($file) use ($cms) {
    $cms->css[] = "map_search/bower_components/jqueryui/themes/kfl-theme/$file.css";
    }, array('jquery-ui.min',) // 'jquery-ui-1.10.3.custom') 
);

$cms->css[] = "map_search/bower_components/jquery-ui-multiselect/jquery.multiselect.css";
$cms->css[] = "map_search/main.css";

array_map(function ($file) use ($cms) {
    $cms->js[] = "map_search/bower_components/$file.js";
}, array(
    // Dependencies
    'jquery/jquery.min',
    'jqueryui/ui/jquery-ui',
    'jquery-collapsible/jquery.collapsible',
    'underscore/underscore-min',
    'backbone/backbone-min',
    'mustache/mustache',
    'openlayers/OpenLayers',
    'jquery-ui-multiselect/src/jquery.multiselect',
    'vc/src/VC',
    'vc/src/SearchResultsLayer',

));

array_map(function ($file) use ($cms) {
    $cms->js[] = "map_search/$file.js";
}, array(
    'AppRoot',
    'input_form/Form',
    'input_form/Query',
    'map/Map',
    'map/ResultLayer',
    'result_list/List',
    'result_list/Collection',
    'result_list/SummaryItem',
    'result_list/Result',
));



$cms->js[] = 'http://maps.googleapis.com/maps/api/js?sensor=false';


?>
<script type="text/template" id="kfa-input-form-template">
    <form action="#" class="search-form">
        <div class="fieldset-header collapsible">Collector<span></span></div>
        <div class="fieldset-body">
            <label for="collector-gender">Gender:</label>
            <select name="collector-gender" class="collector-gender">
                <option value="m">Male</option>
                <option value="f">Female</option>
            </select>
            <label for="collector-occupation">Occupation:</label>
            <input type="text" name="collector-occupation" class="collector-occupation" />
            <label for="collector-age">Age:</label>
            <input type="text" name="collector-age" class="collector-age" />
            <!-- TODO: languages as multiselect -->
            <label for="collector-language">Languages Spoken:</label>
            <input type="text" name="collector-language" class="collector-language" />
            <div class="background-filler"></div>
        </div>
        <div class="fieldset-header collapsible">Consultant<span></span></div>
        <div class="fieldset-body">
            <label for="consultant-gender">Gender:</label>
            <select name="consultant-gender" class="consultant-gender">
                <option value="m">Male</option>
                <option value="f">Female</option>
            </select>
            <label for="consultant-occupation">Occupation:</label>
            <input type="text" name="consultant-occupation" class="consultant-occupation" />
            <label for="consultant-age">Age:</label>
            <input type="text" name="consultant-age" class="consultant-age" />
            <label for="consultant-language">Languages Spoken:</label>
            <input type="text" name="consultant-language" class="consultant-language" />
            <div class="background-filler"></div>
        </div>
        <div class="fieldset-header collapsible">Context<span></span></div>
        <div class="fieldset-body">
            <label for="context-name">Name:</label>
            <input type="text" name="context-name" class="context-name" />
            <label for="context-event-type">Event Type:</label>
            <select name="context-event-type" title="context-event-type" class="context-event-type">
                <optgroup label="Celebration">
                    <option value="Birthday">Birthday</option>
                    <option value="Seasonal/Holiday">Holiday</option>
                    <option value="Wedding">Wedding</option>
                    <option value="Funeral">Funeral</option>
                    <option value="Graduation">Graduation</option>
                    <option value="Other Celebration">Other</option>
                <optgroup>
                <optgroup label="Performance">
                    <option value="Oral History">Oral History</option>
                    <option value="Storytelling">Storytelling</option>
                    <option value="Folk Speech/Gesture">Folk Speech/Gesture</option>
                    <option value="Drama">Drama</option>
                    <option value="Song">Song</option>
                    <option value="Dance">Dance</option>
                    <option value="Other Performance">Other</option>
                </optgroup>
                <optgroup label="Material Culture">
                    <option value="Architecture">Architecture</option>
                    <option value="Costume/Clothing">Costume/ Clothing</option>
                    <option value="Body Art or Adornment">Body Art or Adornment</option>
                    <option value="Folk Art or Craft">Folk Art or Craft</option>
                    <option value="Cooking">Cooking</option>
                    <option value="Other Material Culture">Other</option>
                </optgroup>
                <optgroup label="Other">
                    <option value="General Observation">General Observation</option>
                </optgroup>
            </select>
            <label for="context-time-of-day">Time of Day:</label>
            <select name="context-time-of-day" class="context-time-of-day">
                <option value="morning">Morning</option>
                <option value="afternoon">Afternoon</option>
                <option value="evening">Evening</option>
                <option value="night">Night</option>
            </select>
            <!-- TODO: date span for collection date -->
            <label for="collection-date">Date:</label>
            <label for="collection-weather">Weather:</label>
            <input type="text" name="collection-weather" class="collection-weather" />
            <label for="collection-language">Language:</label>
            <input type="text" name="collection-language" class="collection-language" />
            <label for="collection-place-type">Place Type:</label>
            <select name="collection-place-type" class="collection-place-type">
                <option value="business">Business</option>
                <option value="residence">Residence</option>
                <option value="public">Public Place</option>
            </select>
            <label for="collection-others-present">Number of Others Present:</label>
            <input type="text" name="collection-others-present" class="collection-others-present" />
            <label for="collection-method">Collection Method:</label>
            <select name="collection-method" class="collection-method">
                <option value="tape">Tape Recorder</option>
                <option value="video">Video Camera</option>
                <option value="camera">Still Camera</option>
                <option value="notes">Notes</option>
            </select>
            <label for="collection-description">Description:</label>
            <input type="text" name="collection-description" class="collection-description" />
            <div class="background-filler"></div>
        </div>
        <div class="fieldset-header collapsible">Data<span></span></div>
        <div class="fieldset-body">
            <label for="project-title">Project Title:</label>
            <input type="text" name="project-title" class="project-title" />
            <label for="media">Media:</label>
            <select title="media" class="media">
                <option value="fieldnotes">Field notes</option>
                <option value="images">Images</option>
                <option value="video">Video</option>
                <option value="audio">Audio</option>
            </select>
            <label for="description">Description:</label>
            <input type="text" name="description" class="description" />
            <div class="background-filler"></div>
        </div>
        <input type="submit" name="submit" value="Search" class="search" />
    </form>
</script>

<script type="text/template" id="kfa-summary-item-template">
    <tr>
        <td class="project-title">
            <a href="{{url}}">{{projectTitle}}</a>
        </td>
        <td class="date">{{date}}</td>
        <td class="city">{{city}}</td>
        <td class="description">{{description}}</td>
    </tr>
</script>


<div id="search-wrapper"></div>
<div id="map"></div>
<div id="result-list-wrapper">
    <span class="prev-page">&lt;&lt;</span>
    <span class="next-page">&gt;&gt;</span>
    <table id="result-list">
        <thead>
            <tr>
                <th>Project Title</th>
                <th>Date</th>
                <th>City</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody class="search-results"></tbody>
    </table>
</div>
<script>
$(function () {
    var root = new KFA.AppRoot();
    root.render();
});
</script>