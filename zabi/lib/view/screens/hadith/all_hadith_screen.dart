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
  final HadithController hadithController = Get.put(HadithController());

  @override
  void initState() {
    hadithController.hadithModel = null;
    hadithController.getAllHadithData(Get.arguments[1], Get.arguments[2]);
    super.initState();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      // Appbar start ===>
      appBar: CustomAppBar(
        title: "${Get.arguments[0]} ${"hadith".tr}",
        isBackButtonExist: widget.appBackButton == true ? true : false,
      ),

      // body start ===>
      body: GetBuilder<HadithController>(
        builder: (_) {
          if (hadithController.isLoadingHadith.value) {
            return const Center(child: HadisChapterShimmer());
          }
          final hadiths = hadithController.hadithModel?.hadiths?.data;
          if (hadiths == null || hadiths.isEmpty) {
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
                              color: Theme.of(context)
                                  .textTheme
                                  .bodyMedium!
                                  .color,
                            ),
                          ),
                        ],
                      ),
                      title: Text(
                        hadith.hadithEnglish ?? "",
                        style: robotoMedium.copyWith(
                            fontSize: Dimensions.FONT_SIZE_DEFAULT,
                            overflow: TextOverflow.ellipsis),
                      ),
                      subtitle: Text(
                        "${"writer:".tr} ${hadith.book?.writerName ?? ""}".tr,
                        style: robotoMedium.copyWith(
                            fontSize: Dimensions.FONT_SIZE_DEFAULT),
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
