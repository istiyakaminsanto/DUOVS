@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            {{ $election->name }}
        </h1>
    </section>

  @include('flash::message')


    {{--check error start--}}
    @if($errors->has('candidate_id'))
      <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-ban"></i> Value Must not be empty</h4>
      </div>
    @endif
    @if($errors->has('user_id'))
      <div class="alert alert-danger">
        <h4><i class="icon fa fa-ban"></i> Your Vote Already has Been Taken</h4>
      </div>
    @endif
    {{--check error end--}}


    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    <div class="row">
                <div class="col-md-8">
          <!-- Custom Tabs -->
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class=""><a href="#tab_1" data-toggle="tab" aria-expanded="false">Election</a></li>
              <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false">Result</a></li>
              <li class="active"><a href="#tab_3" data-toggle="tab" aria-expanded="true">Election Details</a></li>
              <li class="dropdown"></li>
              
            </ul>


            <div class="tab-content">
              <div class="tab-pane " id="tab_1">




            {{--Voting time start--}}
          @php
            date_default_timezone_set('Asia/Dhaka');

            $vote_start_time  = strtotime($election->start);
            $vote_end_time = strtotime($election->end);
            $current_date = date('Y-m-d H:i:s', time());
            $current_date_time = strtotime($current_date);
            $differenceInSeconds = $vote_end_time - $vote_start_time;
            $diff_current_time = $current_date_time - $vote_start_time;

            
          @endphp
                  {{--Voting time end--}}

                  {{--if time is between start and end then vote will continue start--}}
          @if($diff_current_time < 0)
          {{"Vote not started yet"}}

          @elseif($diff_current_time > $differenceInSeconds)

          {{ "Voting time is over" }}

          @else


                  <div class="panel panel-primary">
                      <div class="panel-heading">
                          <h3 class="panel-title">
                              <span class="glyphicon glyphicon-arrow-right"></span>Cast Your Vote please </a>
                          </h3>
                      </div>
                      @if(isset($vote_exitsts[0]))
                        <h4>
                          You have already casted your vote.. 
                        </h4>
                      @else

                          @if($election->id == 2)




                              <form action="/votes/count" method="post">
                                  {{ csrf_field() }}
                                  <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                                  <input type="hidden" name="election_id" value="{{ $election->id }}">
                                  <div class="panel-body">
                                      <ul class="list-group">
                                          @if(count($candidate_lists) == 0)
                                              {{ "Admin could not select any candidata" }}
                                          @endif



                                          @foreach($candidate_lists as $candidate_list)
                                              @if($candidate_list->election_id == $election->id)


                                                  <li class="list-group-item">
                                                      <div class="checkbox">
                                                          @if(isset(DB::table('users')->where('id', $candidate_list->user_id)->first()->name))

                                                              <label>
                                                                  <input type="checkbox" name="candidate_id[]" value="{{ DB::table('users')->where('id', $candidate_list->user_id)->first()->id }}">

                                                                  {{ DB::table('users')->where('id', $candidate_list->user_id)->first()->name  }}

                                                              </label>

                                                          @endif
                                                      </div>
                                                  </li>

                                              @endif
                                          @endforeach
                                      </ul>

                                      <div class="panel-footer">
                                          @if(count($candidate_lists) > 0)
                                              <button  type="submit" class="btn btn-success btn-sm">
                                                  Vote
                                              </button>
                                          @endif
                                      </div>
                                  </div>
                              </form>











                          @else

                      <form action="/votes/count" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                        <input type="hidden" name="election_id" value="{{ $election->id }}">
                        <div class="panel-body">
                              <ul class="list-group">
                                @if(count($candidate_lists) == 0)
                                  {{ "Admin could not select any candidata" }}
                                @endif



                               @foreach($candidate_lists as $candidate_list)
                              @if($candidate_list->election_id == $election->id)
                                
                                <li class="list-group-item">
                                    <div class="radio">
                                        @if(isset(DB::table('users')->where('id', $candidate_list->user_id)->first()->name))
                                        
                                        <label>
                                            <input type="radio" name="candidate_id" value="{{ DB::table('users')->where('id', $candidate_list->user_id)->first()->id }}">
                                            
                                            {{ DB::table('users')->where('id', $candidate_list->user_id)->first()->name  }}
                                            
                                        </label>
                                       
                                        @endif
                                    </div>
                                </li>
                                
                                @endif
                                @endforeach 
                            </ul>  
                            
                            <div class="panel-footer">
                              @if(count($candidate_lists) > 0)
                              <button  type="submit" class="btn btn-success btn-sm">
                                  Vote
                              </button>
                              @endif
                            </div>
                        </div>
                      </form>
                          @endif
                          @endif
                    </div>
                  @endif
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_2">
                  @if($diff_current_time < 0)
                  {{"Vote not started yet"}}
                  @else
                <div class="box">
                  
                  <!-- /.box-header -->

                   @if(count($candidate_lists) > 0) 
                  <div class="box-body no-padding">

                    <table class="table table-striped">
                      <tbody>
                          <tr>

                            <th>Candidate Name</th>
                            <th>Progress</th>
                            <th style="width: 40px">Vote</th>
                          </tr>
                             @foreach($candidate_lists as $candidate_list)
                   
                          <tr>

                            <td> {!! DB::table('users')->where('id', $candidate_list->user_id)->first()->name !!} </td>
                            <td>

                              <div class="progress progress-xs">
                                @php
                                $candidate_votes = DB::table('votes')->where(['candidate_id' => $candidate_list->user_id, 'election_id'=> $election->id])->count();
                                $total_votes = count($vote_counts);
                                if($total_votes > 0){
                                $percentage = round(($candidate_votes * 100)/$total_votes, 2);
                                }


                                @endphp


                                <div class="progress-bar progress-bar-info" style="width: @if($total_votes > 0){{$percentage}}@endif%"></div>
                              </div>
                              @if($total_votes > 0){{$percentage}}@endif%
                            </td>
                            <td>
                              <span class="badge bg-red">

                                {!! DB::table('votes')->where(['candidate_id' => $candidate_list->user_id, 'election_id'=> $election->id])->count()  !!}


                              </span>
                            </td>
                          </tr>
                      
                        @endforeach
                    </tbody>
                    </table>
                  </div>@else {{ "Admin could not select any candidate" }} @endif
                  <!-- /.box-body -->
                 
                  
                  
                   <div class="callout callout-success">

                       @if($election->id == 2)
                           <h4>Winners are:</h4>
                           @foreach($member_winner as $single_winner)
                               {!! DB::table('users')->where('id', $single_winner->candidate_id)->first()->name !!}
                               <br>
                           @endforeach
                       @else
                      <h4>
                        @foreach($winner as $single_winner)
                        {!! DB::table('users')->where('id', $single_winner->candidate_id)->first()->name !!} is the winner 
                        @endforeach
                      </h4>
                       @endif

                      <p></p> 
                    </div>
                    
                </div>
                @endif
          
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane active" id="tab_3">
                <h3>Election Name: {{ $election->name }}</h3>
                    {{$election->description}} <br>
                    <b>Election Category:</b> {{ $election->election_category['name'] }} <br>
                    <b>Starting time:</b> {{ $election->start }} <br>
                    <b>Closing time:</b> {{ $election->end }}
              </div>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- nav-tabs-custom -->
        </div>
</div>
                </div>
            </div>
        </div>
    </div>
@endsection
