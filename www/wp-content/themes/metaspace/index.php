<?php get_header(); ?>

      <section class="b-screen b-screen_main js-main-screen">
        <div class="l-inner">
          <div class="b-main-screen__wrapper js-main-screen__block">
            <h1 class="js-main-screen__tlt blured">Molecular annotation engine</h1>
            <div class="b-screen__block">
              <p class="b-screen__lead">
                High-performance algorithms for high-resolution imaging mass spectrometry. Supporting open formats, molecular databases. Delivering molecular annotations, images, biological interpretation.
                <br>
                Free, open for everyone.
              </p><a href="#contacts" class="b-screen__link b-btn_white blur js-main-screen__btn js-scroll-link js-fast-list"><span title="Contact us" class="blur__inner">Contact us</span></a>
            </div>
          </div>
          <footer class="b-screen__footer b-socials"><!--<a href="" class="b-socials__item slack"></a>--><a href="mailto:contact@metaspace2020.eu" class="b-socials__item mail"></a><a href="https://twitter.com/metaspace2020" class="b-socials__item tw"></a></footer>
        </div>
      </section>
      <section class="b-screen b-screen_second">
        <div class="b-screen_second-marks js-marks"><span class="b-mark b-mark_first">Dataset</span><span class="b-mark b-mark_second">Current analysis</span><span class="b-mark b-mark_third">Engine analysis</span><span class="b-mark b-mark_fourth">Molecule A</span><span class="b-mark b-mark_fifth">Molecule B</span></div>
        <div class="l-inner">
          <h2 class="b-screen__tlt js-second-title blured">
            METASPACE
            <br>
            will be able to process up to 100 datasets per day, each of 10 GB
          </h2>
          <div class="b-screen_second-txt b-hiw">
            <?php echo metaspace_texts_get_by_name('how_it_works'); ?>
          </div>
        </div>
      </section>
      <section class="b-screen b-screen_third b-screen_white js-content-start">
        <div class="l-inner">
          <h2>Unified solution for everyone</h2>
          <?php echo metaspace_texts_get_by_name('unified_solution_for_everyone'); ?>
        </div>
      </section>
      <section class="b-screen b-screen_fourth centered">
        <div class="l-inner">
          <h2>Interested to join alpha-testing?</h2>
          <div class="b-subscribe">
            <div class="b-subscribe__tlt lead_dark">Please leave your email</div>
            <form action="/" method="post" id="contact_form_1" class="b-form b-subscribe__form js-form">
              <input type="text" name="contact_email" id="contact_email_1" placeholder="Enter your e-mail" value="" class="b-input">
              <label class="b-btn b-btn_rounded b-btn_arr">
                <input type="submit" value="" class="b-input" onclick="return send_contact('contact_email_1', 'contact_form_1');">
              </label>
              <div class="b-subscribe__message" style="display:none"></div>
            </form>
          </div>
        </div>
      </section>
      <section class="b-screen b-screen_fifth b-screen_white">
        <div class="l-inner">
          <article class="b-article">
            <h2>Uniting expertise to achieve the goal</h2>
            <div class="b-goal">
              <div class="b-goal__item b-goal__item_first">
                <h5 class="b-goal__tlt">Metabolomics</h5>
                <p class="b-goal__txt">The new wave of ‘omics</p>
              </div>
              <div class="b-goal__item b-goal__item_second">
                <h5 class="b-goal__tlt">Imaging mass spectrometry</h5>
                <p class="b-goal__txt">Enabling untargeted metabolic imaging</p>
              </div>
              <div class="b-goal__item b-goal__item_third">
                <h5 class="b-goal__tlt">Software development</h5>
                <p class="b-goal__txt">Turning algorithms into user-friendly tools</p>
              </div>
              <div class="b-goal__item b-goal__item_fourth to-left">
                <h5 class="b-goal__tlt">Mass spectrometry</h5>
                <p class="b-goal__txt">Workhorse of untargeted metabolomics</p>
              </div>
              <div class="b-goal__item b-goal__item_fifth to-left">
                <h5 class="b-goal__tlt">Bioinformatics</h5>
                <p class="b-goal__txt">Key to interpretation of big imaging data</p>
              </div>
            </div>
          </article>
          <article class="b-article b-consortium">
            <h2 id="consortium" class="js-title">Consortium</h2>
            <p class="lead">The project is funded by European Commission as a part<br> of&nbsp;<a href="" class="gray-link">Horizon 2020</a> program and unites 8 partners from 6 countries.</p>
            <div class="b-tabs js-tabs">
              <div class="b-tabs__links js-tabs__links">
                <a href="" class="b-tabs__link js-tabs__link fake-link active">EMBL</a>
                <a href="" class="b-tabs__link js-tabs__link fake-link">EBI — EMBL</a>
                <a href="" class="b-tabs__link js-tabs__link fake-link">ICL</a>
                <a href="" class="b-tabs__link js-tabs__link fake-link">SCiLS</a>
                <a href="" class="b-tabs__link js-tabs__link fake-link">UCSD</a>
                <a href="" class="b-tabs__link js-tabs__link fake-link">UR1</a>
                <a href="" class="b-tabs__link js-tabs__link fake-link">VIB</a>
                <a href="" class="b-tabs__link js-tabs__link fake-link">ERS</a>
              </div>
              <div class="b-tabs__blocks js-tabs__blocks">
                <div class="b-tabs__block js-tabs__block b-tab-block">
                  <div class="b-tab-block__left"><span class="b-tab-block__img-wrap"><img src="files/EMBL-logo.jpg" alt="" class="b-tab-block__img"></span>
                    <footer class="b-tabs-block__footer"><a href="https://www.embl.de" class="b-tab-block__cmp gray-link"><span>European Molecular Biology Laboratory</span></a><span class="b-tabs-block__loc">Heidelberg, Germany</span></footer>
                  </div>
                  <div class="b-tab-block__right">
                    <h3 class="b-tab-block__tlt">Theodore Alexandrov's team</h3>
                    <footer class="b-tabs-block__footer">
                      <p class="b-tab-block__descr">We coordinate the project, participate in most of interactions, write part of algorithms and response for outreach.</p>
                    </footer>
                  </div>
                </div>
                <div class="b-tabs__block js-tabs__block b-tab-block">
                  <div class="b-tab-block__left"><span class="b-tab-block__img-wrap"><img src="files/EMBL_EBI_logo.jpg" alt="" class="b-tab-block__img"></span>
                    <footer class="b-tabs-block__footer"><a href="http://www.ebi.ac.uk" class="b-tab-block__cmp gray-link"><span>European Bioinformatics Institute</span></a><span class="b-tabs-block__loc">Hinxton, United Kingdom</span></footer>
                  </div>
                  <div class="b-tab-block__right">
                    <h3 class="b-tab-block__tlt">Christoph Steinbeck</h3>
                    <footer class="b-tabs-block__footer">
                      <p class="b-tab-block__descr">EBI leads outreach activities.</p>
                    </footer>
                  </div>
                </div>
                <div class="b-tabs__block js-tabs__block b-tab-block">
                  <div class="b-tab-block__left"><span class="b-tab-block__img-wrap"><img src="files/logo_imperial_college_london1.png" alt="" class="b-tab-block__img"></span>
                    <footer class="b-tabs-block__footer"><a href="https://www.imperial.ac.uk" class="b-tab-block__cmp gray-link"><span>Imperial College London</span></a><span class="b-tabs-block__loc">London, United Kingdom</span></footer>
                  </div>
                  <div class="b-tab-block__right">
                    <h3 class="b-tab-block__tlt">Zoltan Takats, Kirill Veselkov</h3>
                    <footer class="b-tabs-block__footer">
                      <p class="b-tab-block__descr">ICL leads efforts on integrating LC-MS data, analysis of DESI-imaging data, and a case study on esophageal cancer.</p>
                    </footer>
                  </div>
                </div>
                <div class="b-tabs__block js-tabs__block b-tab-block">
                  <div class="b-tab-block__left"><span class="b-tab-block__img-wrap"><img src="files/SCiLS_logo.png" alt="" class="b-tab-block__img"></span>
                    <footer class="b-tabs-block__footer"><a href="http://scils.de" class="b-tab-block__cmp gray-link"><span>SCiLS</span></a><span class="b-tabs-block__loc">Bremen, Germany</span></footer>
                  </div>
                  <div class="b-tab-block__right">
                    <h3 class="b-tab-block__tlt">Dennis Trede</h3>
                    <footer class="b-tabs-block__footer">
                      <p class="b-tab-block__descr">SCiLS contributes with expertise in software development.</p>
                    </footer>
                  </div>
                </div>
                <div class="b-tabs__block js-tabs__block b-tab-block">
                  <div class="b-tab-block__left"><span class="b-tab-block__img-wrap"><img src="files/ucsd-logo.jpg" alt="" class="b-tab-block__img"></span>
                    <footer class="b-tabs-block__footer"><a href="http://ucsd.edu" class="b-tab-block__cmp gray-link"><span>University of California San Diego</span></a><span class="b-tabs-block__loc">La Jolla, CA, USA</span></footer>
                  </div>
                  <div class="b-tab-block__right">
                    <h3 class="b-tab-block__tlt">Pieter Dorrestein</h3>
                    <footer class="b-tabs-block__footer">
                      <p class="b-tab-block__descr">UCSD leads a case study on cystic fibrosis.</p>
                    </footer>
                  </div>
                </div>
                <div class="b-tabs__block js-tabs__block b-tab-block">
                  <div class="b-tab-block__left"><span class="b-tab-block__img-wrap"><img src="files/University_of_Rennes.png" alt="" class="b-tab-block__img"></span>
                    <footer class="b-tabs-block__footer"><a href="https://international.univ-rennes1.fr" class="b-tab-block__cmp gray-link"><span>University of Rennes 1</span></a><span class="b-tabs-block__loc">Rennes, France</span></footer>
                  </div>
                  <div class="b-tab-block__right">
                    <h3 class="b-tab-block__tlt">Charles Pineau</h3>
                    <footer class="b-tabs-block__footer">
                      <p class="b-tab-block__descr">UR1 contributes by providing high-resolution imaging mass spectrometry infrastructure.</p>
                    </footer>
                  </div>
                </div>
                <div class="b-tabs__block js-tabs__block b-tab-block">
                  <div class="b-tab-block__left"><span class="b-tab-block__img-wrap b-tab-block__img-wrap_bg_gray"><img src="files/VIB_LOGO_rgb_neg_LARGE.png" alt="" class="b-tab-block__img"></span>
                    <footer class="b-tabs-block__footer"><a href="http://www.vib.be" class="b-tab-block__cmp gray-link"><span>Vlaams Instituut voor Biotechnologie</span></a><span class="b-tabs-block__loc">Ghent, Belgium</span></footer>
                  </div>
                  <div class="b-tab-block__right">
                    <h3 class="b-tab-block__tlt">Lennart Martens</h3>
                    <footer class="b-tabs-block__footer">
                      <p class="b-tab-block__descr">VIB contributes with the expertise in computational mass spectrometry and machine learning.</p>
                    </footer>
                  </div>
                </div>
                <div class="b-tabs__block js-tabs__block b-tab-block">
                  <div class="b-tab-block__left"><span class="b-tab-block__img-wrap"><img src="files/LOGO_TIFF.jpg" alt="" class="b-tab-block__img"></span>
                    <footer class="b-tabs-block__footer"><a href="http://european-research-services.eu" class="b-tab-block__cmp gray-link"><span>European Research Services</span></a><span class="b-tabs-block__loc">Muenster, Germany</span></footer>
                  </div>
                  <div class="b-tab-block__right">
                    <h3 class="b-tab-block__tlt">Oliver Panzer</h3>
                    <footer class="b-tabs-block__footer">
                      <p class="b-tab-block__descr">ERS performs non-scientific management.</p>
                    </footer>
                  </div>
                </div>
              </div>
            </div>
          </article>
          <section class="b-article b-article_advisory">
            <h2 id="advisory" class="js-title">Advisory Board</h2>
            <?php echo metaspace_texts_get_by_name('advisory_board'); ?>
          </section>
        </div>
      </section>
      <section class="b-screen b-screen_six">
        <div class="l-inner">
          <h2>From now on METASPACE needs your attention</h2>
          <p class="lead_dark">Please email us if interested to use METASPACE or would like to contribute</p>
          <div class="b-columns">
            <div class="b-columns__item b-columns__item_three"><a href="https://twitter.com/metaspace2020" class="b-soc-link tw">@metaspace2020<span class="b-soc-link__descr">Project news</span></a></div>
            <div class="b-columns__item b-columns__item_three"><a href="mailto:contact@metaspace2020.eu" class="b-soc-link mail">contact@metaspace2020.eu</a></div>
            <div class="b-columns__item b-columns__item_three"><a href="" class="b-soc-link slack">#metaspace2020<span class="b-soc-link__descr">Educational channel</span></a></div>
          </div>
        </div>
      </section>
      <section class="b-screen b-screen_seven b-screen_white">
        <div class="l-inner">
          <h2 id="publications" class="js-title">Publications</h2>
          <div class="b-columns">
            <?php $publication_list = metaspace_publication_get_all_public(); ?>
            <?php if(!empty($publication_list) && is_array($publication_list)) { foreach($publication_list as $publication) {  ?>
            <div class="b-columns__item b-columns__item_two">
              <article class="b-publications"><span class="b-publications__date date"><?php echo $publication['fadte']; ?></span>
                <h5 class="b-publications__tlt"><?php echo $publication['anons']; ?></h5>
                <?php echo $publication['authors']; ?>
                <p title="<?php echo $publication['source']; ?>" class="b-publications__info"><?php echo $publication['source']; ?></p>
                <footer class="b-publications__footer"><a href="<?php echo $publication['link']; ?>" class="b-btn b-btn_rounded b-btn_fasten"></a><!--<a href="<?php echo IMPROVE_UPLOAD_PUBLICATION_URL.$publication['file']; ?>" class="b-btn b-btn_rounded b-btn_download"></a>--></footer>
              </article>
            </div>
            <?php }} ?>
          </div>
        </div>
      </section>
      <section class="b-screen b-screen_eight b-screen_white">
        <div class="l-inner">
          <h2 id="events" class="js-title">Events</h2>
          <div class="b-slider-wrapper js-carousel-wrapper">
            <div class="b-slider js-slider owl-carousel">
              <?php $events_list = metaspace_events_get_all_public(false); ?>
              <?php if(!empty($events_list) && is_array($events_list)) { foreach($events_list as $event) {  ?>
              <article class="b-slider__item"><?php if(!empty($event['preview'])) { ?><img src="<?php echo IMPROVE_UPLOAD_EVENTS_URL.$event['preview']; ?>" alt="" class="b-slider__img"><?php } ?><span class="b-slider__date date"><?php echo $event['fs_date']; if(!empty($event['f_date'])) { echo ' — '.$event['fe_date']; } ?></span>
                <h4 class="b-slider__tlt"><?php echo $event['name']; ?></h4>
                <p class="b-slider__txt"><?php echo $event['anons']; ?></p>
                <footer class="b-slider__footer">
                  <p><a href="<?php if(!empty($event['place_link'])) { echo $event['place_link']; } else { echo '#'; } ?>" class="link-ico link-ico_pos"><?php echo $event['place']; ?></a></p>
                  <p><a href="<?php echo $event['link']; ?>" class="link-ico link-ico_site"><span class="gray-link">Site of conference</span></a></p>
                </footer>
              </article>
              <?php }} ?>
            </div>
            <nav class="b-slider-nav"><span class="b-btn b-btn_rounded b-btn_left owl-btn_prev js-btn"></span><span class="b-btn b-btn_rounded b-btn_right owl-btn_next js-btn"></span></nav>
          </div>
          <h3>Past events</h3>
          <div class="b-slider-wrapper js-carousel-wrapper">
            <div class="b-slider js-slider owl-carousel">
              <?php $events_list = metaspace_events_get_all_public(true); ?>
              <?php if(!empty($events_list) && is_array($events_list)) { foreach($events_list as $event) {  ?>
                <article class="b-slider__item b-slider__item_small"><span class="b-slider__date date"><?php echo $event['fs_date']; if(!empty($event['f_date'])) { echo ' — '.$event['fe_date']; } ?></span>
                <h4 class="b-slider__tlt"><?php echo $event['name']; ?></h4>
                <p class="b-slider__txt"><?php echo $event['anons']; ?></p>
                <footer class="b-slider__footer">
                  <?php if(!empty($event['file'])) { ?><p><a href="<?php echo IMPROVE_UPLOAD_EVENTS_URL.$event['file']; ?>" class="link-ico link-ico_site"><span class="gray-link">See report</span></a></p><?php } ?>
                </footer>
              </article>
              <?php }} ?>
            </div>
            <nav class="b-slider-nav"><span class="b-btn b-btn_rounded b-btn_left owl-btn_prev js-btn"></span><span class="b-btn b-btn_rounded b-btn_right owl-btn_next js-btn"></span></nav>
          </div>
        </div>
      </section>
      <section class="b-screen b-screen_nine b-screen_white">
        <div class="l-inner">
          <h2>Recent tweets</h2>
          <div class="b-tweets">
            <?php $tweets_data = metaspace_get_tweets(); ?>
            <?php if(!empty($tweets_data) && is_array($tweets_data)) { foreach($tweets_data as $tweet) {  ?>
            <div class="b-tweets__item">
              <header class="b-tweets__header">
                <h3><?php echo $tweet['name']?></h3><a href="" class="b-tweets__name gray-link">@<?php echo $tweet['screen_name']?></a><span class="b-tweets__date date"><?php echo $tweet['time']?></span>
              </header>
              <div class="b-tweets__body">
                <p class="b-tweets__content"><?php echo $tweet['text']?></a></p>
              </div>
            </div>
            <?php }} ?>
          </div>
            <a href="<?php echo get_option('metaspace_twitter_link'); ?>" class="b-screen__link b-btn b-btn_blur js-main-screen__btn"><span title="Read all" class="blur__inner">Read all</span></a>
        </div>
      </section>

<?php get_footer(); ?>
