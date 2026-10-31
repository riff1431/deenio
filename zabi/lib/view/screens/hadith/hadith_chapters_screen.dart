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
  late final HadithController ctrl;
  String bookSlug = 'sahih-bukhari';
  String bookName = 'Sahih Bukhari';

  @override
  void initState() {
    super.initState();
    ctrl = Get.find<HadithController>();
    if (Get.arguments != null && Get.arguments is List && (Get.arguments as List).isNotEmpty) {
      bookSlug = Get.arguments[0]?.toString() ?? 'sahih-bukhari';
      if ((Get.arguments as List).length > 1) {
        bookName = Get.arguments[1]?.toString() ?? 'Sahih Bukhari';
      }
    }
    // Fetch chapters for this book
    ctrl.getHadithBookChaptersData(bookSlug);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: CustomAppBar(
        title: "$bookName ${"chapters".tr}",
        isBackButtonExist: widget.appBackButton,
      ),
      body: Obx(() {
        // Loading
        if (ctrl.isLoadingChapters.value) {
          return const Center(child: HadisChapterShimmer());
        }
        // Empty
        final chapters = ctrl.chaptersRx.value?.chapters;
        if (chapters == null || chapters.isEmpty) {
          return Center(
            child: Padding(
              padding: const EdgeInsets.all(24),
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Icon(Icons.menu_book_outlined,
                      size: 64, color: Theme.of(context).primaryColor),
                  const SizedBox(height: 16),
                  Text("no_hadith_found_in_this_chapter".tr,
                      textAlign: TextAlign.center,
                      style: robotoMedium.copyWith(fontSize: 16)),
                ],
              ),
            ),
          );
        }
        // Data
        return ListView.builder(
          padding: const EdgeInsets.symmetric(
              horizontal: Dimensions.PADDING_SIZE_SMALL),
          itemCount: chapters.length,
          itemBuilder: (context, index) {
            final chapter = chapters[index];
            return GestureDetector(
              onTap: () {
                Get.toNamed(RouteHelper.allHadith, arguments: [
                  bookName,
                  chapter.bookSlug ?? bookSlug,
                  chapter.chapterNumber ?? (index + 1),
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
                  leading: CircleAvatar(
                    backgroundColor: Theme.of(context).primaryColor,
                    child: Text(
                      "${index + 1}",
                      style: robotoMedium.copyWith(
                          fontSize: Dimensions.FONT_SIZE_DEFAULT,
                          color: Colors.white),
                    ),
                  ),
                  title: Text(
                    chapter.chapterEnglish ?? "",
                    style: robotoMedium.copyWith(
                        fontSize: Dimensions.FONT_SIZE_DEFAULT),
                  ),
                  subtitle: Text(
                    chapter.chapterArabic ?? "",
                    textAlign: TextAlign.right,
                    style: robotoRegular.copyWith(
                        fontSize: Dimensions.FONT_SIZE_DEFAULT,
                        color: Theme.of(context).primaryColor),
                  ),
                ),
              ),
            );
          },
        );
      }),
    );
  }
}
