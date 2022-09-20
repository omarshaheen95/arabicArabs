{{--Dev Omar Shaheen
    Devomar095@gmail.com
    WhatsApp +972592554320
    --}}
@extends('user.layout.container_v2')
@section('style')
    <link href="{{asset('s_website/css/animate_cards.css')}}" rel="stylesheet">
@endsection
@section('content')
    <section class="login-home user-home">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-6">
                    <a href="{{route('levels')}}" class="login-type">
                        <div class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="104.491" height="89.454" viewBox="0 0 104.491 89.454">
                                <g id="document" transform="translate(0 -36.839)">
                                    <path id="Path_99026" data-name="Path 99026" d="M103.042,58.992,65.349,37.231a2.9,2.9,0,0,0-3.964,1.061l-2.6,4.5a6.405,6.405,0,0,0-1.13-.1H32.03a1.531,1.531,0,1,0,0,3.061H57.657A3.342,3.342,0,0,1,61,49.086v70.808a3.342,3.342,0,0,1-3.339,3.337H12.46a3.342,3.342,0,0,1-3.339-3.337v-9.246h3.868a2.194,2.194,0,0,0,2.192-2.192v-3.966a2.194,2.194,0,0,0-2.192-2.192H2.192A2.194,2.194,0,0,0,0,104.491v3.966a2.194,2.194,0,0,0,2.192,2.192H6.059v9.246a6.407,6.407,0,0,0,6.4,6.4h45.2a6.406,6.406,0,0,0,6.4-6.4V92.165l9.737,5.622a1.531,1.531,0,1,0,1.531-2.651L64.057,88.631V83.894l13.319,7.689a1.531,1.531,0,1,0,1.531-2.651L64.057,80.359V75.622l16.9,9.758a1.531,1.531,0,1,0,1.531-2.651L64.057,72.087V67.35L84.539,79.176a1.531,1.531,0,1,0,1.531-2.651L64.057,63.816V49.087A6.388,6.388,0,0,0,61.6,44.049l2.36-4.087,37.418,21.6-32.4,56.123a1.531,1.531,0,0,0-1.389,2.724,2.879,2.879,0,0,0,1.445.388,2.91,2.91,0,0,0,2.52-1.45l32.56-56.394A2.911,2.911,0,0,0,103.042,58.992ZM3.061,105.361h9.058v2.227H3.061Z" fill="#fff"/>
                                    <path id="Path_99027" data-name="Path 99027" d="M356.522,116.568l-16.79-9.694a1.531,1.531,0,0,0-1.531,2.651l16.79,9.694a1.531,1.531,0,1,0,1.531-2.651Z" transform="translate(-268.569 -55.578)" fill="#fff" opacity="0.4"/>
                                    <path id="Path_99028" data-name="Path 99028" d="M2.192,83.439H6.059V94.785a1.531,1.531,0,1,0,3.061,0V83.439h3.868a2.194,2.194,0,0,0,2.192-2.192V77.281a2.194,2.194,0,0,0-2.192-2.192H9.121V71.9a3.342,3.342,0,0,1,3.339-3.337H25.909a1.531,1.531,0,0,0,0-3.061H12.46a6.406,6.406,0,0,0-6.4,6.4v3.193H2.192A2.194,2.194,0,0,0,0,77.281v3.966A2.194,2.194,0,0,0,2.192,83.439Zm.87-5.289h9.058v2.227H3.061Z" transform="translate(0 -22.81)" fill="#fff"/>
                                    <path id="Path_99029" data-name="Path 99029" d="M2.192,243.388H6.06v12.093a1.531,1.531,0,0,0,3.061,0V243.388h3.868a2.194,2.194,0,0,0,2.192-2.191V237.23a2.194,2.194,0,0,0-2.192-2.192H2.192A2.194,2.194,0,0,0,0,237.23V241.2A2.194,2.194,0,0,0,2.192,243.388Zm.87-5.288h9.058v2.227H3.061Z" transform="translate(0 -157.749)" fill="#fff" opacity="0.4"/>
                                    <path id="Path_99030" data-name="Path 99030" d="M103.629,137.344V145.3a2.635,2.635,0,0,0,2.632,2.632h22.553a2.635,2.635,0,0,0,2.632-2.632v-7.955a2.635,2.635,0,0,0-2.632-2.632H106.261A2.635,2.635,0,0,0,103.629,137.344Zm3.061.429h21.695v7.1H106.69Z" transform="translate(-82.48 -77.898)" fill="#fff" opacity="0.4"/>
                                </g>
                            </svg>
                        </div>
                        <div class="content">
                            <h2 class="title"> المستويات والدروس </h2>
                            <h2 class="title"> Levels & Lessons </h2>
                        </div>
                    </a>
                </div>
                <div class="col-lg-5 col-md-6">
                    <a href="{{route('levels.stories')}}" class="login-type">
                        <div class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="107.588" height="89.454" viewBox="0 0 107.588 89.454">
                                <g id="book" transform="translate(-9.207 -45.123)">
                                    <path id="Path_99001" data-name="Path 99001" d="M122.987,208.57a1.3,1.3,0,0,1-.73-.224c-10.4-7.02-23.82-3.308-23.955-3.27a1.308,1.308,0,0,1-.717-2.515c.6-.17,14.74-4.076,26.135,3.618a1.308,1.308,0,0,1-.733,2.392Z" transform="translate(-68.371 -122.228)" fill="#223f99"/>
                                    <path id="Path_99002" data-name="Path 99002" d="M122.989,246.15a1.3,1.3,0,0,1-.758-.243c-8.959-6.385-23.846-3.085-23.995-3.052a1.308,1.308,0,1,1-.582-2.55c.656-.15,16.19-3.587,26.095,3.471a1.308,1.308,0,0,1-.76,2.373Z" transform="translate(-68.373 -151.882)" fill="#223f99" opacity="0.4"/>
                                    <path id="Path_99003" data-name="Path 99003" d="M122.99,285.173a1.3,1.3,0,0,1-.781-.26c-7.021-5.237-23.918-3.019-24.088-3a1.308,1.308,0,1,1-.351-2.592c.737-.1,18.135-2.377,26,3.491a1.308,1.308,0,0,1-.783,2.356Z" transform="translate(-68.373 -182.749)" fill="#223f99"/>
                                    <path id="Path_99004" data-name="Path 99004" d="M122.989,324.432a1.3,1.3,0,0,1-.7-.207c-9.832-6.309-23.877-2.859-24.018-2.823a1.308,1.308,0,1,1-.641-2.536c.62-.156,15.3-3.757,26.072,3.158a1.308,1.308,0,0,1-.708,2.409Z" transform="translate(-68.373 -213.234)" fill="#223f99" opacity="0.4"/>
                                    <path id="Path_99005" data-name="Path 99005" d="M289.77,208.566a1.308,1.308,0,0,1-.733-2.392c11.4-7.694,25.539-3.788,26.135-3.618a1.308,1.308,0,0,1-.717,2.515c-.133-.038-13.585-3.731-23.955,3.27A1.3,1.3,0,0,1,289.77,208.566Z" transform="translate(-218.384 -122.225)" fill="#223f99"/>
                                    <path id="Path_99006" data-name="Path 99006" d="M289.77,246.153a1.308,1.308,0,0,1-.76-2.373c9.9-7.058,25.438-3.621,26.095-3.471a1.308,1.308,0,1,1-.582,2.55c-.149-.034-15.053-3.321-23.995,3.052A1.3,1.3,0,0,1,289.77,246.153Z" transform="translate(-218.385 -151.885)" fill="#223f99"/>
                                    <path id="Path_99007" data-name="Path 99007" d="M289.769,285.173a1.308,1.308,0,0,1-.783-2.356c7.869-5.868,25.265-3.591,26-3.491a1.308,1.308,0,1,1-.351,2.592c-.17-.022-17.075-2.234-24.088,3a1.3,1.3,0,0,1-.781.26Z" transform="translate(-218.384 -182.749)" fill="#223f99" opacity="0.4"/>
                                    <path id="Path_99008" data-name="Path 99008" d="M258.349,106.73a4.992,4.992,0,1,0,4.992,4.992A4.992,4.992,0,0,0,258.349,106.73Zm0,7.367a2.376,2.376,0,1,1,2.376-2.376,2.376,2.376,0,0,1-2.376,2.376Z" transform="translate(-190.932 -48.178)" fill="#223f99"/>
                                    <path id="Path_99009" data-name="Path 99009" d="M159.009,96.214a3.9,3.9,0,1,0,3.9,3.9A3.9,3.9,0,0,0,159.009,96.214Zm0,5.183a1.284,1.284,0,1,1,1.283-1.283,1.284,1.284,0,0,1-1.283,1.283Z" transform="translate(-114.1 -39.955)" fill="#223f99"/>
                                    <path id="Path_99010" data-name="Path 99010" d="M107.159,45.123a4.21,4.21,0,1,0,4.21,4.21A4.21,4.21,0,0,0,107.159,45.123Zm0,5.8a1.594,1.594,0,1,1,1.594-1.594,1.594,1.594,0,0,1-1.594,1.594Z" transform="translate(-73.309)" fill="#223f99" opacity="0.4"/>
                                    <path id="Path_99011" data-name="Path 99011" d="M334.243,51.724a4.924,4.924,0,1,0,4.924,4.924A4.924,4.924,0,0,0,334.243,51.724Zm0,7.232a2.309,2.309,0,1,1,2.308-2.308,2.309,2.309,0,0,1-2.308,2.308Z" transform="translate(-250.336 -5.162)" fill="#223f99" opacity="0.4"/>
                                    <path id="Path_99012" data-name="Path 99012" d="M418.029,79.7a1.308,1.308,0,0,1-1.308-1.308V77.216a1.308,1.308,0,0,1,2.616,0v1.175A1.308,1.308,0,0,1,418.029,79.7Z" transform="translate(-318.688 -24.075)" fill="#223f99"/>
                                    <path id="Path_99013" data-name="Path 99013" d="M418.029,109.2a1.308,1.308,0,0,1-1.308-1.308v-1.175a1.308,1.308,0,0,1,2.616,0v1.175A1.308,1.308,0,0,1,418.029,109.2Z" transform="translate(-318.688 -47.147)" fill="#223f99"/>
                                    <path id="Path_99014" data-name="Path 99014" d="M401.759,95.969h-1.175a1.308,1.308,0,0,1,0-2.616h1.175a1.308,1.308,0,1,1,0,2.616Z" transform="translate(-305.045 -37.717)" fill="#223f99"/>
                                    <path id="Path_99015" data-name="Path 99015" d="M431.259,95.969h-1.175a1.308,1.308,0,0,1,0-2.616h1.175a1.308,1.308,0,1,1,0,2.616Z" transform="translate(-328.115 -37.717)" fill="#223f99"/>
                                    <path id="Path_99016" data-name="Path 99016" d="M55.348,83.93a1.308,1.308,0,0,1-1.308-1.308v-.545a1.308,1.308,0,1,1,2.616,0v.545A1.308,1.308,0,0,1,55.348,83.93Z" transform="translate(-35.061 -27.876)" fill="#223f99"/>
                                    <path id="Path_99017" data-name="Path 99017" d="M55.348,107.391a1.308,1.308,0,0,1-1.308-1.308v-.545a1.308,1.308,0,0,1,2.616,0v.545A1.308,1.308,0,0,1,55.348,107.391Z" transform="translate(-35.061 -46.223)" fill="#223f99"/>
                                    <path id="Path_99018" data-name="Path 99018" d="M42.912,96.366h-.545a1.308,1.308,0,1,1,0-2.616h.545a1.308,1.308,0,1,1,0,2.616Z" transform="translate(-24.909 -38.028)" fill="#223f99"/>
                                    <path id="Path_99019" data-name="Path 99019" d="M66.374,96.366h-.545a1.308,1.308,0,1,1,0-2.616h.545a1.308,1.308,0,0,1,0,2.616Z" transform="translate(-43.257 -38.028)" fill="#223f99"/>
                                    <path id="Union_5" data-name="Union 5" d="M-551.408-507.916l-.385-.385a1.309,1.309,0,0,1,0-1.849,1.307,1.307,0,0,1,1.85,0l.385.385a1.308,1.308,0,0,1,.284,1.425,1.308,1.308,0,0,1-1.208.807h0A1.3,1.3,0,0,1-551.408-507.916Zm-3.076.383a1.308,1.308,0,0,1-1.209-.807,1.306,1.306,0,0,1,.284-1.425l.385-.385a1.307,1.307,0,0,1,1.849,0,1.309,1.309,0,0,1,0,1.849l-.385.385a1.3,1.3,0,0,1-.921.383Zm3.616-3.616a1.308,1.308,0,0,1-1.209-.807,1.308,1.308,0,0,1,.283-1.425l.385-.385a1.307,1.307,0,0,1,1.849,0,1.308,1.308,0,0,1,0,1.849l-.385.385a1.3,1.3,0,0,1-.921.383Zm-4.156-.382-.385-.385a1.307,1.307,0,0,1,0-1.849,1.307,1.307,0,0,1,1.849,0l.385.385a1.308,1.308,0,0,1,.284,1.425,1.308,1.308,0,0,1-1.209.807h0A1.3,1.3,0,0,1-555.025-511.532Z" transform="translate(611.531 564.328)" fill="#223f99" opacity="0.4"/>
                                    <path id="Path_99024" data-name="Path 99024" d="M114.344,137.667c-.665-.257-3.957-1.1-4.443-1.225a4.071,4.071,0,0,0-3.1-3.45c-12.693-3.041-23.734-3.056-32.818-.044a29.3,29.3,0,0,0-11,6.221A29.26,29.26,0,0,0,52,132.947a51.582,51.582,0,0,0-21.366-1.915c-.041-.025-.083-.051-.126-.074l-1.324-.724-.725-1.325a2.985,2.985,0,0,0-5.237,0l-.724,1.324-1.325.725a2.982,2.982,0,0,0-1.467,1.917c-.169.039-.337.076-.507.116a4.071,4.071,0,0,0-3.1,3.451c-.485.131-3.778.968-4.443,1.225a3.864,3.864,0,0,0-2.45,3.584v46.761a6.848,6.848,0,0,0,8.457,6.647c11.111-2.661,20.537-3.436,28.015-2.3,6.771,1.027,9.946,3.376,11,4.338a8.334,8.334,0,0,0,5.642,2.191h1.365a8.335,8.335,0,0,0,5.642-2.191c1.051-.962,4.227-3.311,11-4.339,7.479-1.135,16.9-.361,28.015,2.3a6.848,6.848,0,0,0,8.457-6.647v-46.76A3.864,3.864,0,0,0,114.344,137.667Zm-50.053,3.889c2.291-2.39,14.08-12.686,41.9-6.021a1.465,1.465,0,0,1,1.133,1.426v46.875a.551.551,0,0,1-.218.444.534.534,0,0,1-.47.1,73.066,73.066,0,0,0-19.1-2.343c-9.375.18-17.182,2.514-23.241,6.94Zm-41.859-8.3,1.9-1.04a.371.371,0,0,0,.147-.147l1.04-1.9a.369.369,0,0,1,.648,0l1.04,1.9a.371.371,0,0,0,.147.147l1.9,1.04a.369.369,0,0,1,0,.648l-1.9,1.04a.368.368,0,0,0-.147.147l-1.04,1.9a.369.369,0,0,1-.648,0l-1.04-1.9a.369.369,0,0,0-.147-.147l-1.9-1.04a.369.369,0,0,1,0-.648Zm-3.752,3.708a1.465,1.465,0,0,1,1.133-1.426c.154-.037.305-.07.458-.105a2.979,2.979,0,0,0,.908.765l1.324.725.724,1.324a2.985,2.985,0,0,0,5.237,0l.724-1.325,1.325-.725a2.984,2.984,0,0,0,1.553-2.619c0-.015,0-.029,0-.044,19.1-1.531,27.693,6.02,29.612,8.023v47.394c-6.054-4.41-13.848-6.735-23.205-6.915q-.581-.011-1.155-.011a73.7,73.7,0,0,0-17.947,2.354.536.536,0,0,1-.47-.1.551.551,0,0,1-.218-.444Zm95.5,51.053a4.232,4.232,0,0,1-5.231,4.1c-11.44-2.741-21.2-3.529-29.017-2.343-7.211,1.094-10.846,3.6-12.371,5a5.724,5.724,0,0,1-3.875,1.5H62.319a5.723,5.723,0,0,1-3.875-1.5c-1.526-1.4-5.16-3.9-12.371-5-7.815-1.186-17.577-.4-29.017,2.343a4.232,4.232,0,0,1-5.231-4.1V141.251a1.232,1.232,0,0,1,.778-1.145c.519-.2,3.192-.881,3.464-.951v44.681a3.168,3.168,0,0,0,3.962,3.073c6.292-1.638,27.883-6.1,41.548,5.274a2.229,2.229,0,0,0,2.852,0c13.665-11.373,35.257-6.912,41.548-5.274a3.168,3.168,0,0,0,3.963-3.073v-44.68c.272.07,2.945.75,3.463.951a1.232,1.232,0,0,1,.778,1.145Z" transform="translate(0 -64.309)" fill="#223f99"/>
                                    <path id="Path_99025" data-name="Path 99025" d="M321.469,308.588,319,307.24l-1.348-2.465a1.825,1.825,0,0,0-3.2,0L313.1,307.24l-.4.221c-4.72-.772-15.333-1.687-23.636,3.64a1.308,1.308,0,0,0,1.412,2.2c6.269-4.022,14.273-4.071,19.274-3.573a1.817,1.817,0,0,0,.889,2.061l2.465,1.348,1.348,2.465a1.825,1.825,0,0,0,3.2,0L319,313.139l2.465-1.348a1.826,1.826,0,0,0,0-3.2Zm-3.923,2.368a1.823,1.823,0,0,0-.724.724l-.576,1.053a.218.218,0,0,1-.382,0l-.575-1.051a1.828,1.828,0,0,0-.726-.727l-1.051-.575a.218.218,0,0,1,0-.383l1.051-.575a1.827,1.827,0,0,0,.726-.726l.575-1.051a.218.218,0,0,1,.383,0l.575,1.051a1.826,1.826,0,0,0,.726.726L318.6,310a.218.218,0,0,1,0,.383Z" transform="translate(-218.386 -202.312)" fill="#223f99"/>
                                </g>
                            </svg>
                        </div>
                        <div class="content">
                            <h2 class="title"> مكتبة القصص </h2>
                            <h2 class="title"> The Arabic library </h2>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
