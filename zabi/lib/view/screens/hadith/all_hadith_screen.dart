// ignore_for_file: deprecated_member_use

import 'package:flutter/material.dart';
import 'package:flutter_svg/flutter_svg.dart';
import 'package:get/get.dart';
import 'package:zabi/shimmer/all_shimmer_loder.dart';
import 'package:zabi/view/base/custom_app_bar.dart';

import '../../../controller/hadith_controller.dart';
import '../../../helper/route_helper.dart';
import '../../../util/dimensions.dart';
import '../../../util/images.dart';
import '../../../util/styles.dart';

class AllHadithScreen extends StatefulWidget {
  final bool appBackButton;
  const AllHadithScreen({super.key, required this.appBackButton});

  @override
  State<AllHadithScreen> createState() => _AllHadithScreenState();
}

class _AllHadithScreenState extends State<AllHadithScreen> {
  late final HadithController ctrl;
  String bookName = 'Sahih Bukhari';
  String bookSlug = 'sahih-bukhari';
  dynamic chapterNumber = 1;

  @override
  void initState() {
    super.initState();
    ctrl = Get.find<HadithController>();
    if (Get.arguments != null && Get.arguments is List && (Get.arguments as List).isNotEmpty) {
      bookName = Get.arguments[0]?.toString() ?? 'Sahih Bukhari';
      if ((Get.arguments as List).length > 1) {
        bookSlug = Get.arguments[1]?.toString() ?? 'sahih-bukhari';
      }
      if ((Get.arguments as List).length > 2) {
        chapterNumber = Get.arguments[2] ?? 1;
      }
    }
    ctrl.getAllHadithData(bookSlug, chapterNumber);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: CustomAppBar(
        title: "$bookName ${"hadith".tr}",
        isBackButtonExist: widget.appBackButton,
      ),
      body: Obx(() {
        // Loading
        if (ctrl.isLoadingHadith.value) {
          return const Center(child: HadisChapterShimmer());
        }
        // Empty
        final hadiths = ctrl.hadithsRx.value?.hadiths?.data;
        if (hadiths == null || hadiths.isEmpty) {
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
          itemCount: hadiths.length,
          itemBuilder: (context, index) {
            final hadith = hadiths[index];
            return GestureDetector(
              onTap: () {
                Get.toNamed(RouteHelper.hadithDetails, arguments: hadith);
              },
              child: Card(
                clipBehavior: Clip.antiAlias,
                color: Theme.of(context).cardColor,
                child: ListTile(
                  contentPadding: const EdgeInsets.symmetric(
                      horizontal: Dimensions.PADDING_SIZE_EXTRA_SMALL),
                  horizontalTitleGap: 5,
                  leading: Stack(
                    alignment: Alignment.center,
                    children: [
                      SvgPicture.asset(
                        Images.Icon_Star,
                        height: 50,
                        fit: BoxFit.fill,
                        color: Theme.of(context).primaryColor,
                      ),
                      Text(
                        (index + 1).toString(),
                        style: robotoMedium.copyWith(
                          fontSize: Dimensions.FONT_SIZE_DEFAULT,
                          color: Theme.of(context).textTheme.bodyMedium?.color,
                        ),
                      ),
                    ],
                  ),
                  title: Text(
                    hadith.hadithEnglish ?? "",
                    maxLines: 2,
                    overflow: TextOverflow.ellipsis,
                    style: robotoMedium.copyWith(
                        fontSize: Dimensions.FONT_SIZE_DEFAULT),
                  ),
                  subtitle: Text(
                    "${"writer:".tr} ${hadith.book?.writerName ?? ""}",
                    style: robotoMedium.copyWith(
                        fontSize: Dimensions.FONT_SIZE_DEFAULT),
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
