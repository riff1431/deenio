import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:zabi/controller/hadith_controller.dart';
import 'package:zabi/helper/route_helper.dart';
import 'package:zabi/shimmer/all_shimmer_loder.dart';
import 'package:zabi/util/dimensions.dart';
import 'package:zabi/util/styles.dart';
import 'package:zabi/view/base/custom_app_bar.dart';

class HadithChaptersScreen extends StatefulWidget {
  final bool appBackButton;
  const HadithChaptersScreen({super.key, required this.appBackButton});

  @override
  State<HadithChaptersScreen> createState() => _HadithChaptersScreenState();
}

class _HadithChaptersScreenState extends State<HadithChaptersScreen> {
  final HadithController hadithController = Get.put(HadithController());

  @override
  void initState() {
    hadithController.hadithChapterModel = null;
    hadithController.getHadithBookChaptersData(Get.arguments[0]);
    super.initState();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      // Appbar start ===>
      appBar: CustomAppBar(
        title: "${Get.arguments[1]} ${"chapters".tr}",
        isBackButtonExist: widget.appBackButton == true ? true : false,
      ),

      // start body ===>
      body: GetBuilder<HadithController>(
        builder: (_) {
          if (hadithController.isLoadingHadithChapter.value) {
            return const Center(child: HadisChapterShimmer());
          }
          final chapters = hadithController.hadithChapterModel?.chapters;
          if (chapters == null || chapters.isEmpty) {
            return Center(
              child: Padding(
                padding: const EdgeInsets.all(24.0),
                child: Column(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    Icon(Icons.menu_book_outlined,
                        size: 64, color: Theme.of(context).primaryColor),
                    const SizedBox(height: 16),
                    Text(
                      "no_hadith_found_in_this_chapter".tr,
                      textAlign: TextAlign.center,
                      style: robotoMedium.copyWith(fontSize: 16),
                    ),
                  ],
                ),
              ),
            );
          }
          return SingleChildScrollView(
            child: ListView.builder(
              shrinkWrap: true,
              primary: false,
              padding: const EdgeInsets.symmetric(
                  horizontal: Dimensions.PADDING_SIZE_SMALL),
              itemCount: chapters.length,
              itemBuilder: (context, index) {
                return GestureDetector(
                  onTap: () {
                    Get.toNamed(RouteHelper.allHadith, arguments: [
                      Get.arguments[1],
                      chapters[index].bookSlug,
                      chapters[index].chapterNumber,
                    ]);
                  },
                  child: Card(
                    clipBehavior: Clip.antiAlias,
                    color: Theme.of(context).cardColor,
                    child: ListTile(
                      contentPadding: const EdgeInsets.symmetric(
                        horizontal: Dimensions.PADDING_SIZE_DEFAULT,
                        vertical: Dimensions.PADDING_SIZE_EXTRA_SMALL,
                      ),
                      leading: Text(
                        "${index + 1}",
                        style: robotoMedium.copyWith(
                          fontSize: Dimensions.PADDING_SIZE_LARGE,
                          color: Theme.of(context).primaryColor,
                        ),
                      ),
                      title: Text(
                        chapters[index].chapterEnglish ?? "",
                        style: robotoMedium.copyWith(
                          fontSize: Dimensions.FONT_SIZE_DEFAULT,
                          color: Theme.of(context).primaryColor,
                        ),
                      ),
                      trailing: Text(
                        chapters[index].chapterArabic ?? "",
                        style: robotoRegular.copyWith(
                          fontSize: Dimensions.FONT_SIZE_DEFAULT,
                          color: Theme.of(context).primaryColor,
                        ),
                      ),
                    ),
                  ),
                );
              },
            ),
          );
        },
      ),
    );
  }
}
