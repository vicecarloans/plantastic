@extends('master')

@section('style')
    <style type="text/css">

        #description{
            font-family: 'Kaushan Script';
        }

        #map {
            height: 400px;
        }
        #container{
            padding-right:30px;
            padding-left:30px;
        }
        #app{
            background-color:#f9fbff;
        }
    </style>
@stop

@section('body')
    <main>
        <v-content>
            <section >
                <v-container fluid class="transparent" id="container">
                    <v-layout row>
                        <v-flex sm3 class="mr-5">
                            <v-layout column wrap>
                                <v-flex>
                                    <v-card class="transparent elevation-1" tile flat >
                                        <v-card-text id="map"></v-card-text>
                                    </v-card>
                                </v-flex>
                                <v-flex>
                                    <v-expansion-panel style="height:4vh; box-shadow:none;">
                                        <v-expansion-panel-content class="elevation-1">
                                            <div slot="header" style="font-family: 'Belleza', sans-serif; font-size:1.25em;">Category</div>
                                            <v-card>
                                                <v-card-text v-for="(category,i) in categories" :key="i" style="font-family: 'Belleza', sans-serif; font-size:1.25em;">
                                                    <a :href=category.url>@{{ category.text }}</a>
                                                </v-card-text>
                                            </v-card>
                                        </v-expansion-panel-content>
                                        <v-expansion-panel-content class="elevation-1">
                                            <div slot="header" style="font-family: 'Belleza', sans-serif; font-size:1.25em;">Types</div>
                                            <v-card>
                                                <v-card-text v-for="(type,k) in types" :key="k" style="font-family: 'Belleza', sans-serif; font-size:1.25em;">
                                                    <a :href=type.url>@{{ type.text }}</a>
                                                </v-card-text>
                                            </v-card>
                                        </v-expansion-panel-content>
                                    </v-expansion-panel>
                                </v-flex>
                            </v-layout>
                        </v-flex>
                        <v-flex sm12 class="ml-5">
                            <section>
                                <v-layout column wrap>
                                    <v-flex d-flex class="mb-5">
                                        <v-card class="transparent" tile flat>
                                            <v-card-text class="text-xs-center pb-0 mb-0" style="font-family: 'Cinzel', serif;"><h4><b>SIMPLIFYING EVENTS</b></h4></v-card-text>
                                            <v-card-text id="description"><span style="color:red;font-size:2.5em; font-weight:bold; font-family: Kaushan Script', cursive;">Plantastic</span><span style="font-size:1.5em; font-family: 'Lora', serif;">&nbsp; acts as a brand new, efficient and interactive event creation tool for George Brown College Faculty and Students. Our main goal is to <b>facilitate the event creation and management process</b> while creating and maintaining a high quality internal social network.</span></v-card-text>
                                        </v-card>
                                    </v-flex>
                                    <v-flex v-for="(e,i) in event[page-1]" :key="i">
                                        <v-card raised class="mb-4">
                                            <v-container class="white ma-0 pa-0" fluid>
                                                <v-layout column>
                                                    <v-flex xs12>
                                                        <v-layout class="ma-0 pa-0" row>
                                                            <v-flex xs4>
                                                                <a :href="link(e)">
                                                                    <v-card-media height="125px" contain></v-card-media>
                                                                </a>
                                                            </v-flex>
                                                            <v-flex xs7>
                                                                <div class="text-xs-left ma-0 pa-1" style="font-family: 'Lora', serif;">@{{ e.startdate }}</div>
                                                                <div class="headline text-xs-left pl-1" style="font-family: 'Cinzel', serif;"><b>@{{ e.title }}</b></div>
                                                                <div class="text-xs-left ma-0 pa-1" style="font-family: 'Lora', serif;">@{{ e.location }}</div>
                                                            </v-flex>
                                                            <v-flex xs6>
                                                                <v-card-text style="font-family: 'Lora', serif;" v-if="calcPercentage(e) == 100"><v-progress-linear v-model="calcPercentage(e)" v-bind:color="getColor(e)"></v-progress-linear> Event is full</v-card-text>
                                                                <v-card-text style="font-family: 'Lora', serif;" v-else><v-progress-linear v-model="calcPercentage(e)" v-bind:color="getColor(e)"></v-progress-linear> @{{ e.registered_amount }} / @{{ e.capacity }} people has registered</v-card-text>
                                                            </v-flex>
                                                        </v-layout>
                                                    </v-flex>
                                                    <v-flex xs12>
                                                        <v-layout row>
                                                            <v-flex d-flex class="text-xs-center grey lighten-3" xs4>
                                                                <v-card-text class=" pt-1" style="font-family: 'Lora', serif;">@{{ e.price }}</v-card-text>
                                                            </v-flex>
                                                            <v-flex class="text-xs-left" style="border-top-style:solid; border-top-width:1px" xs7>
                                                                <a href="#" class="pa-1 ma-1" style="font-family: 'Lora', serif;"><strong>#@{{ types[e.event_type_id].text }}</strong></a>
                                                                <a href="#" class="pa-1 ma-1" style="font-family: 'Lora', serif;"><strong>#@{{ categories[e.category_id].text }}</strong></a>
                                                            </v-flex>
                                                            <v-flex d-flex class="text-xs-right" style="border-top-style:solid; border-top-width:1px"  xs6>
                                                                <v-tooltip bottom>
                                                                    <v-btn slot="activator" icon @click.native.stop="getShareLink(i)"><v-icon>share</v-icon></v-btn>
                                                                    <span>Share</span>
                                                                </v-tooltip>
                                                            </v-flex>
                                                        </v-layout>
                                                    </v-flex>
                                                </v-layout>
                                            </v-container>
                                            <v-dialog v-model="share" max-width="400" persistent>
                                                <v-card>
                                                    <v-container fluid>
                                                        <v-layout column wrap>
                                                            <v-flex xs12>
                                                                <v-card-title class="headline">Copy this link to share</v-card-title>
                                                            </v-flex>
                                                            <v-flex xs12>
                                                                <v-text-field v-model="shareLink" readonly autofocus></v-text-field>
                                                            </v-flex>
                                                            <v-flex xs12>
                                                                <v-card-actions>
                                                                    <v-spacer></v-spacer>
                                                                    <v-btn color="green darken-1" flat="flat" @click="share=false">Done</v-btn>
                                                                </v-card-actions>
                                                            </v-flex>
                                                        </v-layout>
                                                    </v-container>
                                                </v-card>
                                            </v-dialog>
                                        </v-card>
                                    </v-flex>
                                    <v-flex>
                                        <div class="text-xs-center">
                                            <v-pagination :length="pages" v-model="page" :total-visible="7" circle></v-pagination>
                                        </div>
                                    </v-flex>
                                </v-layout>
                            </section>
                        </v-flex>
                    </v-layout>
                </v-container>
            </section>
        </v-content>
    </main>
@stop

@section('script')

    <script>
        var allType = <?php echo json_encode($event_type); ?>;
        var allCategory = <?php echo json_encode($category); ?>;
        var allEvent = <?php echo json_encode($pagi); ?>;
        console.log(event);
        function initMap() {
            var uluru = {lat: 43.6532, lng: -79.3832};
            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 4,
                center: uluru
            });
            var marker = new google.maps.Marker({
                position: uluru,
                map: map
            });
        }

        new Vue({
            el: '#app',
            data: {
                event: allEvent,
                shareLink:'',
                share: false,
                page: 1,
                categories: allCategory,
                types: allType
            },
            methods: {
                calcPercentage: function(e){
                    return (e.registered_amount/e.capacity)*100
                },
                getShareLink: function(index){
                    this.shareLink = "https://php-project-willieduke.c9users.io/event/" + this.event[this.page-1][index].id
                    this.share = true
                },
                getColor: function(event){
                    if(this.calcPercentage(event) <= 30){
                        return "green"
                    }else if(this.calcPercentage(event) <= 60){
                        return "blue"
                    }else {
                        return "red"
                    }
                },
                link: function(event) {
                    return '/event/'+ event.id
                }
            },
            computed:{
                pages: function() {
                    return this.event.length
                }

            }
        })
    </script>

    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDuP7giqQQp4O8FE8oL41qjWLyFlcv3Ws8&callback=initMap">
    </script>

@stop